<?php

namespace App\Console\Commands;

use App\Policy;
use App\PolicyTemplate;
use HTMLPurifier_Config;
use Illuminate\Console\Command;

/**
 * Class CleanupPolicyHtml.
 *
 * Cleans up character formatting in PolicyTemplates and Policies.
 *
 * Cleanup includes removing font styles and sizes, while keeping tags like
 * p, h1, h2, ul, li, strong, em and so on.
 *
 * This can be run as needed and doesn't need to be scheduled in cron.
 */
class CleanupPolicyHtml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanupPolicyHtml {business_id?} {--templates}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans up character formatting in PolicyTemplates and Policies';

    /**
     * A counter used inside both handle() and the closure inside it (hence needing to be a property).
     * @var int
     */
    protected $count = 0;

    /**
     * Config for the HTMLPurifier.
     */
    private $purifier_config;

    /**
     * CleanupPolicyHtml constructor. Configures HTMLPurifier.
     */
    public function __construct()
    {
        parent::__construct();

        // this command uses a custom HTMLPurifier config, different from the standard Laravel one.
        $conf = HTMLPurifier_Config::createDefault();
        $conf->set('Cache.DefinitionImpl', null); // turns off caching
        $conf->set('HTML.AllowedElements', [
                'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span',
                'table', 'thead', 'tbody', 'tfoot', 'tr', 'td', 'ul', 'ol', 'li',
                'b', 'strong', 'i', 'em', 'u', 's', 'strike', 'a', 'br', 'img',
                'ins', 'del',   // these last two are used by the "track changes" plugin
            ]);
        $conf->set('HTML.AllowedAttributes', [
            'div.style', 'p.style', 'span.style', 'table.style', 'table.border', 'table.cellpadding', 'table.cellspacing',
            'td.style', 'a.href', 'img.width', 'img.height', 'img.alt', 'img.src',
            // the ones below are used by the "track changes" plugin. (sadly, there's no wildcard option in HTMLPurifier)
            'ins.class', 'ins.data-changedata', 'ins.data-cid', 'ins.data-last-change-time', 'ins.data-time', 'ins.data-userid', 'ins.data-username',
            'del.class', 'del.data-changedata', 'del.data-cid', 'del.data-last-change-time', 'del.data-time', 'del.data-userid', 'del.data-username',
        ]);
        $conf->set('CSS.AllowedProperties', 'border,border-collapse,border-bottom,border-top,border-left,border-right,'.
            'font-weight,font-style,padding,text-align,width');
        $conf->set('AutoFormat.AutoParagraph', true);
        $conf->set('AutoFormat.RemoveEmpty', true);
        // remove empty paras with only spaces in them (`<p> </p>`)
        $conf->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);
        // removes spans that had all their attrs removed
        $conf->set('AutoFormat.RemoveSpansWithoutAttributes', true);

        // HTMLPurifier doesn't allow "data-*" attributes by default, so we need to add them
        $def = $conf->getHTMLDefinition(true);
        $def->addAttribute('ins', 'data-changedata', 'CDATA');
        $def->addAttribute('ins', 'data-cid', 'CDATA');
        $def->addAttribute('ins', 'data-last-change-time', 'CDATA');
        $def->addAttribute('ins', 'data-time', 'CDATA');
        $def->addAttribute('ins', 'data-userid', 'CDATA');
        $def->addAttribute('ins', 'data-username', 'CDATA');
        $def->addAttribute('del', 'data-changedata', 'CDATA');
        $def->addAttribute('del', 'data-cid', 'CDATA');
        $def->addAttribute('del', 'data-last-change-time', 'CDATA');
        $def->addAttribute('del', 'data-time', 'CDATA');
        $def->addAttribute('del', 'data-userid', 'CDATA');
        $def->addAttribute('del', 'data-username', 'CDATA');

        $this->purifier_config = $conf;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $business_id = $this->argument('business_id');

        // Global Policy Templates (use `php artisan cleanupPolicyHtml --templates` to clean these)

        $options = $this->options();

        if ($options['templates']) {
            $templates = PolicyTemplate::all();
            foreach ($templates as $t) {
                $this->line(" ----- Cleaning HTML for PolicyTemplate '{$t->admin_name}' ----- ");
                $t->content = $this->sanitize($t->content);
                $t->save();
            }

            return;
        }

        // Policies for the Businesses

        $policies = Policy::select(['id', 'business_id', 'manual_name', 'content', 'content_raw']);
        if (! is_null($business_id)) {
            $policies->where('business_id', $business_id);
        }

        // using "chunk" here to limit memory usage by processing a few rows at a time
        // (see https://laravel.com/docs/5.7/eloquent#chunking-results )
        $policies->chunk(50, function ($policies) {
            foreach ($policies as $policy) {
                $this->line(" ----- Cleaning HTML for Policy '{$policy->manual_name}' for business #{$policy->business_id} ----- ");

                $content = $this->sanitize($policy->content);
                $content_raw = $this->sanitize($policy->content_raw);

                if ($content != $policy->content || $content_raw != $policy->content_raw) {
                    $this->line('Done.');
                    $policy->content = $content;
                    $policy->content_raw = $content_raw;
                    $policy->save();
                    $this->count++;
                } else {
                    $this->line('Pre- and post- content matched. Skipping.');
                }
            }
        });
        $this->line("Complete. $this->count policies updated.");
    }

    /**
     * Uses HTMLPurifier to clean up the HTML in a string.
     * @param $str
     * @return mixed|string|string[]|null
     */
    private function sanitize($str)
    {

        // using HTML Purifier to clean up disallowed tags and styles <style> tags and "style=" attributes

        // Note: we're instantiating the regular HTMLPurifier class, rather than using the laravel "Purifier" facade
        // because the facade wasn't handling the configuration correctly.
        $purifier = new \HTMLPurifier($this->purifier_config);
        $str = $purifier->purify($str);

        // clean up some weirdly nested styles, like "font-weight: normal" inside "font-weight: bold"
        $str = preg_replace(
            '/<span style="font-weight:bold;"><span style="font-weight:normal;">(.*)<\/span><\/span>/',
            '\\1',
            $str
        );
        $str = preg_replace(
            '/<span style="font-style:italic;"><span style="font-style:normal;">(.*)<\/span><\/span>/',
            '\\1',
            $str
        );
        // and turn the remaining bold and italic styles into the real HTML tags
        $str = preg_replace(
            '/<span style="font-style:italic;">(.*)<\/span>/',
            '<em>\\1</em>',
            $str
        );
        $str = preg_replace(
            '/<strong>(.*)<\/strong>/',
            '<strong>\\1</strong>',
            $str
        );

        return $str;
    }
}
