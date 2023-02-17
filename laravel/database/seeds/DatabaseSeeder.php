<?php

use App\Business;
use App\BusinessAsas;
use App\BusinessPermission;
use App\Role;
use App\UserPolicyUpdates;
use App\BonusPro\Plan;
use App\BonusPro\Month;
use App\BonusPro\MonthUser;
use App\TimeOff;
use App\User;
use App\Category;
use App\FormTemplate;
use App\PolicyTemplate;
use Bentericksen\Payment\Products\Models\StripeProduct;
use Bentericksen\Payment\Prices\Models\StripePrice;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET foreign_key_checks=0');

        User::truncate();
        Role::truncate();
        DB::table('role_user')->truncate();
        Category::truncate();
        FormTemplate::truncate();
        PolicyTemplate::truncate();
        Business::truncate();
        BusinessPermission::truncate();
        BusinessAsas::truncate();
        TimeOff::truncate();
        Plan::truncate();
        Month::truncate();
        MonthUser::truncate();
        UserPolicyUpdates::truncate();
        StripeProduct::truncate();
        StripePrice::truncate();

        $this->call(RolesTableSeeder::class);
        $this->call(BusinessTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(TimeOffTableSeeder::class);
        $this->call(StripeProductsTableSeeder::class);
        $this->call(StripePricesTableSeeder::class);

        DB::statement(file_get_contents(sprintf('%s/categories.sql', dirname(__FILE__))));
        DB::statement(file_get_contents(sprintf('%s/form_templates.sql', dirname(__FILE__))));
        DB::statement(file_get_contents(sprintf('%s/policy_templates.sql', dirname(__FILE__))));
    }
}
