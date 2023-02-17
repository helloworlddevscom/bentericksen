<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Bentericksen\Payment\PaymentHelper;
use Bentericksen\Payment\Clients\StripeClient;

class StripeProductsTableSeeder extends Seeder
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
        $products = [];
        if($this->environment !== "production") {
            $data = PaymentHelper::objToArray(StripeClient::sandbox()->products->all()['data']);
        } else {
            $data = PaymentHelper::objToArray(StripeClient::production()->products->all()['data']);
        }

        foreach($data as $item)
        {
            if(!isset($item['metadata']['type']) || $item['metadata']['type'] !== 'hrdirector_payment_ui') {
                continue;
            }
            $products[] = [
                'id' => $item['id'],
                'name' => $item['name']
            ];
        }

        print_r($products);

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('stripe_products')->truncate();
        DB::table('stripe_products')->insert($products);
    }
}
