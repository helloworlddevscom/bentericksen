<?php

namespace App\Console\Commands;

use App\Business;
use App\Policy;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

set_time_limit(6000); 
ini_set("memory_limit", -1);

class UniquePolicies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uniquePolicies {--fix} {--report}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report & Remove duplicate policies (excluding stub based policies)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $fix = $this->option('fix');

      $report = $this->option('report');

      $policies = $this->getDuplicatePolicies();
      
      if ($report) {
        var_dump($policies);
      }

      foreach ($policies as $policy) {
        $this->evaluateAndUpdate($policy, $fix);
      }
      
    }

    protected function getDuplicatePolicies() {
      $this->info('Generating working set...');

      return DB::select( DB::raw("select b.id, b.name, p.template_id, COUNT(*)
      from policies p inner join business b on p.business_id = b.id
      where (p.special is null OR p.special = '') and p.status <> 'closed' and p.is_custom = 0 and p.deleted_at is null
      group by b.id, b.name, p.template_id
      having COUNT(*) > 1"));
    }

    protected function getWorkingSet(bool $fix): array
    {
      $report = $this->option('report');
      $start = time();

      if (!$report) {
        $this->info('Generating working set...');
      }

      Policy::select(['id', 'business_id', 'template_id'])
        ->whereNotNull('template_id')
        //->whereNotIn('template_id', [277, 282])
        ->where(function ($query) {
          $query->whereNull('special');
          $query->orWhere('special', '');
        })
        ->where('status', '<>', 'closed')
        ->where('is_custom', 0)
        ->where('is_modified', 0)
        ->where('edited', 'no')
        ->chunk(200, function ($policies) use ($fix) {
          
          $policiesCount = $policies->count();

          $uniquePolicies = $policies->unique(function($policy) {
            return "{$policy->template_id}:{$policy->business_id}";
          });
          
          $uniquePoliciesCount = $uniquePolicies->count();

          foreach ($uniquePolicies as $policy) {
            $this->evaluateAndUpdate($policy, $fix);
          }
        });
    }

    protected function evaluateAndUpdate(\stdClass $policy, $fix = false): void
    {
      [$policies, $duplicates, $count] = $this->findDuplicatePolicies($policy);

      if (!$count) {
        return;
      }

      $keepKey = $policies->get(0)->id;
      $remKeys = $duplicates->pluck('id')->join(',');
      
      $strPolicy = Str::of('policy')->plural($count);

      $this->info("Found {$count} duplicate $strPolicy for template id {$policy->template_id} and business {$policy->id}");
      $this->info("Keeping policy: {$keepKey}. Removing $strPolicy: {$remKeys}");

      if (!$fix) {
        return;
      }

      $duplicates->each(function($duplicate) {
        $this->info("Deleting policy {$duplicate->id}");

        $duplicate->update([
          'delete_reason' => 'Duplicate cleanup job'
        ]);
        $duplicate->delete();
      });
    }

    protected function report($uniquePolicies): void
    {
      $this->line('Policy ID, "Policy Name", "Policy ID", "Template ID"');

      foreach ($uniquePolicies as $policy) {
        $this->reportLine($policy);
      }
    }

    protected function reportLine(Policy $policy): void
    {
      [$policies, $duplicates, $count] = $this->findDuplicatePolicies($policy);

      if (!$count) {
        return;
      }
      
      foreach ($duplicates as $duplicate) {
        $this->line(sprintf('"%s", "%s", "%s", %s, %s', $duplicate->business->name, $duplicate->business->contactName, $duplicate->manual_name, $duplicate->id, $duplicate->template_id));
      }
    }

    protected function findDuplicatePolicies(\stdClass $policy): array
    {
      $policies = Policy::where('template_id', $policy->template_id)
        ->where('business_id', $policy->id)
        ->where('status', '<>', 'closed')
        ->where(function ($query) {
          $query->whereNull('special');
          $query->orWhere('special', '');
        })
        ->where('is_custom', 0)
        ->where('is_modified', 0)
        ->where('edited', 'no')
        ->get();

      $duplicates = $policies->slice(1);
      $count = $duplicates->count();

      return [$policies, $duplicates, $count];
    }

    public function execTime($start, $task): void
    {
      $execTime = time() - $start;
      $this->info("Executed $task in {$execTime}s");
    }
}
