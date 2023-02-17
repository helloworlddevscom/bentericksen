<?php

namespace App\Console\Commands;

use App\Business;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExportPolicyFiles extends Command
{

  /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exportPolicyFiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get all policy-files and export';



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $this->info('starting up!');
      $manuals = $this->getManuals();
      
    }

    public function getManuals()
    {
      $this->info('Getting Manuals');

      $manuals = [];

      DB::table('business')
        ->whereNotNull('manual')
        ->orderBy('id')
        ->chunk(100, function($businesses) use (&$manuals) {
          $businesses
            ->each(function($business) use (&$manuals) {
              $state = $business->state;
              $name = $business->name;
              $time = date('Y-m-d', strtotime($business->manual_created_at));
              $destination = sprintf('bentericksen/policy-archive/%s-%s-%s.pdf', $state, $time, $name );
              try {
                copy(
                  storage_path('bentericksen/policy-backup/' . $business->manual),
                  storage_path($destination)
                );
                array_push($manuals, $destination);
              } catch(\Exception $e) {
                $this->error($e->getMessage());
              }
            });

            $manuals_count = count($manuals);

            $this->info("Got $manuals_count Manuals");
        });

        return $manuals;
    }

}