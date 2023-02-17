<?php

namespace App\Console\Commands;

use App\Business;
use App\Policy;
use Illuminate\Console\Command;

class BusinessPoliciesCleanUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'businessPoliciesCleanUp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean-up of Business Policies.';

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
     * @return mixed
     */
    public function handle()
    {
        $businesses = Business::where('id', '>', 2)
            ->whereIn('status', ['active', 'renewed'])
            ->get();

        foreach ($businesses as $business) {
            // Skipping businesses without policies set. This business never
            // logged in on the server.
            if (! $business->policies()->count()) {
                continue;
            }
            $this->line(' --- Processing business #'.$business->id.': '.$business->name.' ---');

            $this->cleanupStub($business, 'vacation/pto', 'VACATION/PTO');
            $this->cleanupStub($business, 'dental_benefits', 'DENTAL BENEFITS');
        }

        // clean up a few stubs that "fell through the cracks" of the logic above.
        // (I found the IDs here by running a SQL query - KD)
        Policy::where('special', 'stub')
            ->whereIn('id', [23053, 23055, 23057, 24757, 24758, 24762,
                22051, 22053, 26527, 26529, 26532, 26534, 25358, 25360, 25362,
                13719, 13234, ])->delete();
    }

    /**
     * @param \App\Business $business
     * @param string $type "vacation/pto" or "dental_benefits"
     * @param string $manual_name "VACATION/PTO" or "DENTAL BENEFITS"
     */
    protected function cleanupStub($business, $type, $manual_name)
    {
        // Getting available stubs
        $stubs = $business->policies->where('manual_name', $manual_name)
            ->where('special', 'stub');

        /*
         * @todo: remove extra stubs.
         */
        if ($stubs->count() > 1) {
            $this->line('Multiple '.$manual_name.' stubs for business: '.$business->id.' | '.$business->name);
            // usually the duplicate stubs all have the same status and "extra" data.
            // so we can easily just delete all but the first one in the set.
            $status = 'this will match nothing';
            $extra = '';
            foreach ($stubs as $st) {
                if ($st->status === $status && $st->special_extra === $extra) {
                    $this->line('Deleting duplicate stub #'.$st->id);
                    $st->delete();
                }
                $status = $st->status;
                $extra = $st->special_extra;
            }

            return;
        }

        $stub = $stubs->first();

        // if didn't match a stub, see if there's a non-stub policy with the same name
        // (if so, we don't need to do anything.)
        if (! $stub) {
            $policy = $business->policies->where('manual_name', $manual_name)->first();
            if ($policy) {
                // The business has a policy that isn't associated with a stub.
                // We don't to create or update stubs in this case, so just return.
                return;
            }
        }

        // if stub doesn't exist, and no policy exists either, create a stub
        if (! $stub) {
            $this->createStub($type, $manual_name, $business);

            return;  // we don't need to run the logic below if we just created a stub.
        }

        $extra = json_decode($stub->special_extra);
        $selectedPolicy = $business->policies->whereIn('template_id', $extra->policies);

        if ($selectedPolicy->count() > 0) {
            // if business has chosen a policy, the stub should be disabled
            if ($stub->status == 'enabled') {
                $this->line('Disabling '.$type.' stub #'.$stub->id);
                $stub->status = 'closed';
                $stub->save();
            }
        } elseif ($selectedPolicy->count() == 0) {
            // stub should be enabled if the business hasn't chosen a policy
            if ($stub->status !== 'enabled') {
                $this->line('Enabling '.$type.' stub #'.$stub->id);
                $stub->status = 'enabled';
                $stub->save();
            }
        }
    }

    /**
     * @param string $type
     * @param string $manual_name
     * @param $business
     */
    protected function createStub(string $type, $manual_name, $business)
    {
        /*
         * @todo: implement creating stub policy
         */
        $this->line('Creating '.$type.' Stub for business: '.$business->id.' | '.$business->name);

        // first, which policies to use?
        if ($type === 'dental_benefits') {
            $special_extra = [
                'benefit' => 'dental',
                'type' => 'choose',
                'policies' => [276, 277],
                'default' => 277,
            ];
        } else {
            // vacation is different in certain states
            if (in_array($business->state, ['CA', 'IL', 'MA'])) {
                $special_extra = [
                    'benefit' => "pto\/vacation",
                    'type' => 'choose',
                    'policies' => [346, 347],
                    'default' => 346,
                ];
            } else {
                $special_extra = [
                    'benefit' => "pto\/vacation",
                    'type' => 'choose',
                    'policies' => [281, 282],
                    'default' => 281,
                ];
            }
        }
        $stub = new Policy();
        $stub->business_id = $business->id;
        $stub->category_id = 4;
        $stub->manual_name = $manual_name;
        $stub->content = '<h1>If you see this, you need still need to select between multiple policy types</h1>';
        $stub->status = 'enabled';
        $stub->order = $manual_name == 'dental_benefits' ? 4 : 36;  // this will put them in the approximate order, but every business is different, so there's no way to do it perfectly
        $stub->special = 'stub';
        $stub->special_extra = json_encode($special_extra);
        $stub->save();
    }
}
