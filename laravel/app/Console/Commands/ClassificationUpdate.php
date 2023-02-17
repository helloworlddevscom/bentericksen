<?php

namespace App\Console\Commands;

use App\ClassificationUpdates;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class ClassificationUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'classificationUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update classification of employee if effective date is met.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $classification_updates = ClassificationUpdates::where('updated', 0)->get();

        $now = Carbon::now();

        foreach ($classification_updates as $classification) {
            $effective = new Carbon($classification->effective_at);
            if ($now->gte($effective) == true) {
                DB::table('users')->where('id', $classification->user_id)->update(['classification_id' => $classification->classification_id]);

                $classification->updated = 1;
                $classification->save();
            }
        }
    }
}
