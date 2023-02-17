<?php

namespace App\Console\Commands;

use App\Facades\PaymentService;
use Illuminate\Console\Command;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DeleteStripeCustomerTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deleteStripeCustomerTestData
                            {offset : The row offset}
                            {limit : The row limit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete\'s Stripe customer test data from HRDirector along with the corresponding customer records in Stripe.  For use on dev and staging environments.';

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
    public function handle(Response $response)
    {
        if(config('app.env') === "production") {
            $this->line('Exiting. Command not for production environment');
            return 1;
        }

        if($this->confirm(
            sprintf(
                'This will delete all Stripe customer test data on the %s environment.  Do you wish to continue?',
                config('app.env')
            )
        )) {

            $offset = $this->argument('offset');
            $limit = $this->argument('limit');

            $customers = DB::table('users')->select('stripe_id')->where('stripe_id', '<>', null)->offset($offset)->limit($limit)->get();

            $this->line('Customer count: ' . $customers->count());

            if($customers->count() < 1) {
                $this->line('No Stripe customer accounts exist.  Deleting orphaned Stripe records.');
                DB::table('stripe_bank_accounts')->truncate();
                $this->line('Stripe bank account records deleted');
                DB::table('stripe_cards')->truncate();
                $this->line('Stripe credit card records deleted');
                DB::table('stripe_customers')->truncate();
                $this->line('Stripe customer records deleted');
                DB::table('stripe_subscriptions')->truncate();
                $this->line('Stripe subscription records deleted');
                return 0;
            }

            if($this->confirm('To delete customers from Stripe and HRDirector answer YES.  Delete customers from HRDirector only answer NO.')) {
                $this->output->progressStart(count($customers));
                $customers->each(function($customer, $key) use ($response, &$count) {
                    PaymentService::customerComponent()->deleteCustomer($customer, $response);
                    sleep(1);
                    $this->output->progressAdvance();
                });
                $this->output->progressFinish();
            } else {
                $result = $customers->update(['stripe_id' => null]);
                $this->line($result);
            }

            return 0;
        }
    }
}
