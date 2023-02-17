<?php

use App\Policy;
use Illuminate\Database\Seeder;

/**
 * php artisan migrate
 * composer dump-autoload
 * php artisan db:seed --class=SetCustomPolicies.
 */
class SetCustomPolicies extends Seeder
{
    public function run()
    {
        Policy::where('template_id', 0)->update(['is_custom' => true]);
    }
}
