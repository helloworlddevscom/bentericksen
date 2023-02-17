<?php

namespace App\Console;

use App\Console\Commands\AdminStatusSwitch;
use App\Console\Commands\BusinessPoliciesCleanUp;
use App\Console\Commands\ChangeSalary;
use App\Console\Commands\ClassificationUpdate;
use App\Console\Commands\CleanupPolicyHtml;
use App\Console\Commands\CreateAdminUser;
use App\Console\Commands\CreateStripeCustomers;
use App\Console\Commands\DeleteHtmlFiles;
use App\Console\Commands\DeleteStripeCustomerTestData;
use App\Console\Commands\ListBusinessesWithMissingSelectedPolicies;
use App\Console\Commands\OutputConfigValues;
use App\Console\Commands\PatchEnabledStubs;
use App\Console\Commands\PolicyTemplateUpdater;
use App\Console\Commands\PolicyUpdateEmail;
use App\Console\Commands\PolicyUpdateResendEmail;
use App\Console\Commands\RunUserImport;
use App\Console\Commands\SanitizeData;
use App\Console\Commands\SetDoNotContact;
use App\Console\Commands\TestPolicyUpdateProcess;
use App\Console\Commands\UpdateBusinessPolicyStatus;
use App\Console\Commands\UserPolicyUpdates;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\PatchCancelledBusinesses;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ExportPolicyFiles;
use App\Console\Commands\CleanupPolicyArchive;
use App\Console\Commands\CreateMissingBusinessASA;
use App\Console\Commands\AddMissingBusinessIdToUser;
use App\Console\Commands\UniquePolicies;
use App\Console\Commands\ImportProductsToProductionStripe;
use App\Console\Commands\ImportWebhookEndpointToProductionStripe;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AdminStatusSwitch::class,
        ChangeSalary::class,
        CleanupPolicyHtml::class,
        DeleteHtmlFiles::class,
        PolicyUpdateEmail::class,
        PolicyTemplateUpdater::class,
        PolicyUpdateResendEmail::class,
        UserPolicyUpdates::class,
        ClassificationUpdate::class,
        RunUserImport::class,
        CreateAdminUser::class,
        BusinessPoliciesCleanUp::class,
        SanitizeData::class,
        SetDoNotContact::class,
        OutputConfigValues::class,
        TestPolicyUpdateProcess::class,
        PatchEnabledStubs::class,
        CreateStripeCustomers::class,
        PatchCancelledBusinesses::class,
        UpdateBusinessPolicyStatus::class,
        ListBusinessesWithMissingSelectedPolicies::class,
        ExportPolicyFiles::class,
        CleanupPolicyArchive::class,
        DeleteStripeCustomerTestData::class,
        ImportProductsToProductionStripe::class,
        ImportWebhookEndpointToProductionStripe::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('adminStatusSwitch')
            ->daily()
            ->at('3:00')
            ->timezone('America/New_York');

        $schedule->command('changeSalary')
            ->daily()
            ->at('3:00')
            ->timezone('America/New_York');

        $schedule->command('deleteHtmlFiles')
            ->daily()
            ->at('3:00')
            ->timezone('America/New_York');

        $schedule->command('policyUpdateEmail')
            ->daily()
            ->at('3:00')
            ->timezone('America/New_York');

        $schedule->command('policyUpdateResendEmail')
            ->daily()
            ->at('3:00')
            ->timezone('America/New_York');

        $schedule->command('policyTemplateUpdater')
            ->daily()
            ->at('3:00')
            ->timezone('America/New_York');

        $schedule->command('userPolicyUpdates')
            ->daily()
            ->at('3:00')
            ->timezone('America/New_York');

        $schedule->command('classificationUpdate')
            ->daily()
            ->at('3:00')
            ->timezone('America/New_York');

        $schedule->command('cleanupPolicyArchive')
            ->daily()
            ->at('6:00')
            ->timezone('America/New_York');
    }
}
