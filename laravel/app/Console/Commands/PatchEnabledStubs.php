<?php

namespace App\Console\Commands;

use App\Policy;
use Illuminate\Console\Command;

class PatchEnabledStubs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patchEnabledStubs {--dry}';

    public function handle()
    {
        $dry = $this->option('dry');

        $selecteds = Policy::where([
            ['special', 'selected'],
            ['special_extra', ''],
        ])->get();

        foreach ($selecteds as $selected) {
            $stubs = Policy::where([
                ['business_id', $selected->business_id],
                ['special', 'stub'],
            ])->get();

            foreach ($stubs as $stub) {
                $meta = json_decode($stub->special_extra);

                if (! in_array($selected->template_id, $meta->policies)) {
                    continue;
                }

                $this->info(sprintf('Updating Stub: %s Policy: %s', $stub->id, $selected->id));

                if ($dry) {
                    continue;
                }

                $stub->status = 'closed';
                $stub->save();

                $selected->special_extra = $stub->id;
                $selected->include_in_benefits_summary = $stub->include_in_benefits_summary;
                $selected->save();
            }
        }
    }
}
