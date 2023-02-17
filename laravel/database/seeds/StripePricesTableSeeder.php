<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Bentericksen\Payment\PaymentHelper;
use Bentericksen\Payment\Clients\StripeClient;

class StripePricesTableSeeder extends Seeder
{
    private $environment;

    public function __construct()
    {
        $this->environment = config('app.env');
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prices = [];
        if($this->environment !== "production") {
            $productData = PaymentHelper::objToArray(StripeClient::sandbox()->products->all()['data']);
            $priceData = PaymentHelper::objToArray(StripeClient::sandbox()->prices->all(['active' => true, 'limit' => 100])['data']);
        } else {
            $productData = PaymentHelper::objToArray(StripeClient::production()->products->all()['data']);
            $priceData = PaymentHelper::objToArray(StripeClient::production()->prices->all(['active' => true, 'limit' => 100])['data']);
        }

        for($i = 0; $i < count($productData); $i++) {
            if(!isset($productData[$i]['metadata']['type']) || $productData[$i]['metadata']['type'] !== 'hrdirector_payment_ui') {
                continue;
            }
            foreach($priceData as $item) {
                if($item['product'] === $productData[$i]['id']) {
                    $prices[] = [
                        'id' => $item['id'],
                        'prod_id' => $i+1,
                        'unit_amount' => $item['unit_amount'],
                        'description' => $item['nickname']
                    ];
                }
            }
        }

        print_r($prices);

        DB::table('stripe_prices')->truncate();
        DB::table('stripe_prices')->insert($prices);
    }

}
