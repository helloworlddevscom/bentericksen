<?php

namespace App\Console\Commands;

use App\Business;
use Bentericksen\Payment\PaymentHelper;
use Bentericksen\Payment\Clients\StripeClient;
use Illuminate\Console\Command;
use Illuminate\Http\Response;

class ImportWebhookEndpointToProductionStripe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importWebhooksProduction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Stripe webhook endpoints for the current environment if it doesn\'nt already exist.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Response $response)
    {
        $environment = config('app.env');
        $webhookClient = null;
        $webhookEndpoints = null;

        if($environment === "local")
        {
            $this->line("For a local environment you'll need to setup a webhook endpoint manually in Stripe.  Exiting.");
            return 0;
        }

        if($this->confirm(sprintf('This will create a webhook endpoint for the %s environment, only if one doesn\'t already exists.  Continue?', $environment))) {

            switch ($environment)
            {
                case "production":
                    $endpointUrl = "https://hrdirector.bentericksen.com/stripe-webhook";
                    $webhookClient = StripeClient::production();
                    $webhookEndpoints = PaymentHelper::objToArray($webhookClient->webhookEndpoints->all()['data']);
                    break;
                case "staging":
                    $endpointUrl = "https://bentericksen-staging.metaltoad-sites.com/stripe-webhook";
                    break;
                case "dev":
                    $endpointUrl = "https://bentericksen-dev.metaltoad-sites.com/stripe-webhook";
                    break;
                case "feature":
                    $endpointUrl = "https://bentericksen-feature.metaltoad-sites.com/stripe-webhook";
                    break;
            }

            if(is_null($webhookClient)) {
                $webhookClient = StripeClient::sandbox();
                $webhookEndpoints = PaymentHelper::objToArray($webhookClient->webhookEndpoints->all()['data']);
            }

            $webhookExists = false;
            foreach($webhookEndpoints as $endpoint)
            {
                if($endpoint['url'] === $endpointUrl) {
                    $webhookExists = true;
                    break;
                }
            }

            if($webhookExists)
            {
                $this->line($environment . " webhook already exists");
                return 0;
            }

            $webhook = PaymentHelper::objToArray($webhookClient->webhookEndpoints->create([
                'url' => $endpointUrl,
                'enabled_events' => [
                    'customer.source.created',
                    'customer.subscription.created',
                    'customer.discount.updated',
                    'customer.discount.deleted',
                    'customer.discount.created',
                    'customer.source.deleted',
                    'invoice.upcoming',
                    'customer.source.expiring',
                    'customer.updated',
                    'product.updated',
                    'product.deleted',
                    'product.created',
                    'customer.subscription.deleted',
                    'customer.source.updated',
                    'customer.deleted',
                    'charge.succeeded',
                    'charge.failed',
                    'customer.subscription.updated',
                    'invoice.paid',
                    'invoice.payment_failed',
                    'invoice.created',
                ]
            ])['data']);

            print_r($webhook);

            $this->line($environment . " webhook created");
        }
        return 0;
    }
}
