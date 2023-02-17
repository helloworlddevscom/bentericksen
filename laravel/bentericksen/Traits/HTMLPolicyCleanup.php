<?php
namespace Bentericksen\Traits;

use HTMLPurifier_Config;

trait HTMLPolicyCleanup {

    /**
     *
     * Cleans up character formatting in PolicyTemplates and Policies.

     * This will be used in conjunction with a mutator in the policy and policyTemplate models
     * to clean the HTML whenever a user makes an update to a policy (or template)
     *
     * Cleanup includes removing font styles and sizes, while keeping tags like
     * p, h1, h2, ul, li, strong, em and so on.
     */

    /**
     * Config for the HTMLPurifier
     */
    private $purifier_config;

    /**
     * HTMLPurifier setup conditions
     *
     */
    private function HTMLPurifySetup($diff = false)
    {
        // this command uses a custom HTMLPurifier config, different from the standard Laravel one.
        $conf = HTMLPurifier_Config::createDefault();
        $conf->set('Cache.DefinitionImpl', null); // turns off caching
        $conf->set('HTML.AllowedElements', $diff ? [
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span',
            'table', 'thead', 'tbody', 'tfoot', 'tr', 'td', 'ul', 'ol', 'li',
            'em', 'a', 'img'
        ] : [
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span',
            'table', 'thead', 'tbody', 'tfoot', 'tr', 'td', 'ul', 'ol', 'li',
            'b', 'strong', 'i', 'em', 'u', 's', 'strike', 'a', 'br', 'img',
            'ins', 'del'   // these last two are used by the "track changes" plugin
        ]);
        $conf->set('HTML.AllowedAttributes', $diff ? ['img.src'] : [
            'div.style', 'p.style', 'span.style', 'table.style', 'table.border', 'table.cellpadding', 'table.cellspacing',
            'td.style', 'a.href', 'img.width', 'img.height', 'img.alt', 'img.src',
            // the ones below are used by the "track changes" plugin. (sadly, there's no wildcard option in HTMLPurifier)
            'ins.class', 'ins.data-changedata', 'ins.data-cid', 'ins.data-last-change-time', 'ins.data-time', 'ins.data-userid', 'ins.data-username',
            'del.class', 'del.data-changedata', 'del.data-cid', 'del.data-last-change-time', 'del.data-time', 'del.data-userid', 'del.data-username',
        ]);
        $conf->set('CSS.AllowedProperties', 'border,border-collapse,border-bottom,border-top,border-left,border-right,' .
            'font-weight,font-style,padding,text-align,width,margin-top,margin-bottom,margin-left,margin-right');
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
     * Uses HTMLPurifier to clean up the HTML in a string
     *
     * This code was lifted from an artisan command to clean up the HTML.
     *
     * See:  /laravel/app/Console/Commands/CleanupPolicyHtml.php
     * @param $str
     * @return string
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
            "\\1",
            $str
        );
        $str = preg_replace(
            '/<span style="font-style:italic;"><span style="font-style:normal;">(.*)<\/span><\/span>/',
            "\\1",
            $str
        );
        // and turn the remaining bold and italic styles into the real HTML tags
        $str = preg_replace(
            '/<span style="font-style:italic;">(.*)<\/span>/',
            "<em>\\1</em>",
            $str
        );
        $str = preg_replace(
            '/<strong>(.*)<\/strong>/',
            "<strong>\\1</strong>",
            $str
        );


        return trim($str);
    }

    /**
     * @param $content
     * @return string
     */
    public function policyClean( $content = '', $diff = false )
    {
        if (empty($content)) {
            $content = $this->content;
        }
        // Initialize purifier settings
        $this->HTMLPurifySetup($diff);
        return $this->sanitize($content);
    }

}