<?php

namespace App\Console\Commands;

use App\Business;
use Bentericksen\Payment\PaymentHelper;
use Bentericksen\Payment\PaymentService;
use Illuminate\Console\Command;
use Illuminate\Http\Response;

class CreateStripeCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createStripeCustomers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates Stripe Customers for Already Existing Users (Owners)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Response $response)
    {
        if($this->confirm('This will create a Stripe Customer on each Business\' Primary User.  Do you want to continue?')) {
            $businesses = Business::all();
            $this->output->progressStart(count($businesses));
            $successCount = 0;
            $failCount = 0;
            $existsCount = 0;
            foreach($businesses as $business) {
                $result = PaymentService::createStripeAccount($business, $response);
                if(!$result['success'] && isset($result['skip'])) {
                    $existsCount++;
                } else if($result['success']) {
                    $successCount++;
                } else {
                    $failCount++;
                }
                $this->output->progressAdvance();
            }
            $this->output->progressFinish();
            $this->line('Added: ' . $successCount);
            $this->line('Failed: ' . $failCount);
            $this->line('Already Existed: ' . $existsCount);
        }
        return 0;
    }
}
