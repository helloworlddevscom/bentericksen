<?php

namespace App\Console\Commands;

use App\Business;
use App\PolicyTemplate;
use App\PolicyTemplateUpdate;
use App\PolicyUpdater;
use App\User;
use Bentericksen\Policy\PolicyRules;
use Bentericksen\PolicyUpdater\PolicyUpdater as BentPolicyUpdater;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class TestPolicyUpdateProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testPolicyUpdateProcess {policyId} {email}';

    public function handle()
    {
        $templateId = $this->argument('policyId');
        $testEmail = $this->argument('email');
        $template = PolicyTemplate::findOrFail($templateId);

        $template->effective_date = Carbon::now()->addDay()->toDateString();

        $policyTemplateArray = $template->toArray();

        unset($policyTemplateArray['id']);
        $policyTemplateArray['template_id'] = $templateId;
        $policyTemplateArray['manual_name'] = 'Updated - ' . $policyTemplateArray['manual_name'];
        $policyTemplateArray['content'] = '<h1>Automated Test</h1>'.$policyTemplateArray['content'];
        $policyTemplateUpdate = new PolicyTemplateUpdate($policyTemplateArray);
        $policyTemplateUpdate->save();

        $updater = new BentPolicyUpdater;
        $updater = $updater->create();
        $updater = $updater->load([
            'id' => $updater->id,
            'step' => 4,
            'title' => 'Update from cli',
            'policies' => ["$templateId" => $policyTemplateUpdate->id],
            'emails' => [$testEmail],
            'inactive_clients' => '0',
            'start_date' => '04/10/2020',
            'active_clients_text' => 'Placeholder text - from cli',
            'inactive_clients_text' => 'Placeholder text - from cli',
            'additional_text' => 'Placeholder text - from cli',
        ]);

        $updater->finalize();

        $this->info($updater->id);
    }
}
