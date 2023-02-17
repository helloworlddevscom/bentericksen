<?php

namespace App\Console\Commands;

use App\Business;
use Illuminate\Console\Command;

class ListBusinessesWithMissingSelectedPolicies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'listBusinessesWithMissingSelectedPolicies
                            {offset : The row offset}
                            {limit : The row limit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays a list of businesses and their missing selected policies by template id';

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
        $offset = $this->argument('offset');
        $limit = $this->argument('limit');
        $affectedBusinessPolicies = [];
        $businesses = Business::where('status', '!=', 'expired')->offset($offset)->limit($limit)->get();
        $this->output->progressStart(count($businesses));
        foreach($businesses as $b) {
            $p1 = [];
            $closedStubs = [];
            $policies = $b->policies;
            foreach($policies as $p) {
                if($p->special === "stub" && $p->status === "closed") {
                    array_push($p1, $p);
                }
            }
            foreach ($p1 as $one) {
                $special_extra = json_decode($one->special_extra, true);
                $options = $special_extra['policies'];
                $closedStub = [
                    'id' => $one->id,
                    'business_id' => $one->business_id,
                    'options' => $options
                ];
                if(array_key_exists($one->business_id, $closedStubs)) {
                    array_push($closedStubs[$one->business_id], $closedStub);
                } else {
                    $closedStubs[$one->business_id][0] = $closedStub;
                }
            }
            foreach($closedStubs as $key => &$value) { // $closedStubs[25][1] = ['id' => 1, 'business_id' => 2 , 'options' => [1=>282,2=>283]]
                foreach($value as $_key => &$_value) { // $_key = 1, $_value = ['id' => 1, 'business_id' => 2 , 'options' => [1=>282,2=>283]]
                    // check each closed, stub for a matching non-closed, non-stub policy with the same template id
                    foreach($_value['options'] as $__key => $__value) { // $__key = 1, $__value = 282
                        $policy = $policies
                            ->where('template_id', '=', $__value)
                            ->where('status', '!=', 'closed')
                            ->where('special', '!=', 'stub')->first();
                        // if there's a match, then let's remove the option from the closed stub
                        if(isset($policy)) {
                            unset($_value['options'][$__key]);
                        }
                        // if all but 1 options have a match, then an option has been selected and exists.  Let's remove the item from $closedStubs
                        if(count($_value['options']) < 2) {
                            unset($closedStubs[$key][$_key]);
                        }
                    }
                }
                if(count($closedStubs[$key])) {
                    array_push($affectedBusinessPolicies, $closedStubs);
                }
            }
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();

        $result = [];
        foreach($affectedBusinessPolicies as $abp) {
            foreach($abp as $key => $value) {
                $business_id = $key;
                $options = "";
                foreach($value as $val) {
                    foreach($val['options'] as $_key => $_value) {
                        $options .= $_value . ", ";
                    }
                }
                array_push($result, 'business_id: ' . $business_id . ", options: " . $options);
            }
        }

        print_r($result);
    }
}
