<?php

namespace App\Console\Commands;

use App\Policy;
use App\PolicyTemplate;
use App\PolicyTemplateUpdate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PolicyTemplateUpdater extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'policyTemplateUpdater';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update policy templates when the effective date has been reached';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $policy_templates = PolicyTemplate::all();
        $policy_template_updates = PolicyTemplateUpdate::all();

        $today = Carbon::now();
        $today = $today->format('Y-m-d');

        foreach ($policy_template_updates as $policy_update) {
            $effective_date = new Carbon($policy_update->effective_date);
            $effective_date = $effective_date->format('Y-m-d');

            if ($today == $effective_date) {
                $template_id = $policy_update->template_id;

                // as we are updating this, set all policies below this one a step back in the order to make sure no collisions happen.
                Policy::where('category_id', $policy_update->category_id)
                    ->where('order', '>=', $policy_update->order)
                    ->update([
                        'order' => DB::raw('`order` + 1'),
                    ]);

                unset($policy_update->id);
                unset($policy_update->template_id);
                unset($policy_update->alternate_name);

                $template = $policy_templates->find($template_id);

                $policy_update = $policy_update->toArray();
                
                unset($policy_update['processed']);

                foreach ($policy_update as $key => $value) {
                    $template->$key = $value;
                }

                $template->save();
            }
        }
    }
}
