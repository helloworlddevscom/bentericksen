<?php

namespace App\Console\Commands;

use App\Business;
use Bentericksen\Payment\PaymentHelper;
use Bentericksen\Payment\Clients\StripeClient;
use Illuminate\Console\Command;
use Illuminate\Http\Response;

class ImportProductsToProductionStripe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importProductsProduction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports products from the Stripe sandbox to the production Stripe account.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Response $response)
    {
        if($this->confirm('This will import products from the Stripe sandbox to the production environment.  Continue?')) {

            // Retrieve product data from the sandbox
            $products = PaymentHelper::objToArray(StripeClient::sandbox()->products->all()['data']);
            foreach($products as $prod) {
                // Create products in production using the sandbox data if the product doesn't already exist
                $_products = PaymentHelper::objToArray(StripeClient::production()->products->all()['data']);
                $prodExists = false;
                foreach($_products as $_prod) {
                    if($_prod['name'] === $prod['name']){
                        $this->line($prod['name'] . " already exists.");
                        $prodExists = true;
                        break ;
                    }
                }
                if($prodExists) {
                    continue;
                }
                $product = StripeClient::production()->products->create(['name' => $prod['name']]);
                $this->line($product['name'] . " created.");

                // Retrieve price data from the sandbox for the product
                $prices = StripeClient::sandbox()->prices->all([
                    'limit' => 100,
                    'product' => $prod['id']
                ]);
                foreach($prices as $price) {
                    // Create prices in production using the sandbox data and the id of the new product
                    $priceData = [
                        'product' => $product['id'],
                        'currency' => $price['currency'],
                        'nickname' => $price['nickname'],
                        'unit_amount' => $price['unit_amount']
                    ];
                    if(is_array($price['recurring'])) {
                        $priceData['recurring'] = $price['recurring'];
                    }
                    $price = StripeClient::production()->prices->create($priceData);
                    $this->line($price['nickname'] . " created.");
                }
            }
        }
        return 0;
    }
}
