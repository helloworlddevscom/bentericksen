<?php

namespace App\Console\Commands;

use App\Business;
use App\BusinessAsas;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AdminStatusSwitch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adminStatusSwitch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Changing the Admin Status if ASA is expired';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $asas = BusinessAsas::where('expiration', '<', Carbon::today())->get();

        foreach ($asas as $asa) {
            $business = Business::where('id', $asa->business_id)->first();
            $business_status = Business::where('id', $asa->business_id)->get(['status']);

            if ($asa->business->is_bonus_pro_enabled) {
                $business->update(['hrdirector_enabled' => false]);
            } else {
                if ($business_status[0]->status != 'cancelled') {

                    // Let's set the business' previous status to it's current status before changing its current status to expired.
                    // This is so we can set the business' status back to its previous state upon a successful Stripe Smart Retry.
                    $business->update(['previous_status' => $asa->business->status]);
                    $business->update(['status' => 'expired']);

                    $expired_users = User::where('business_id', $asa->business_id);
                    $expired_users->update(['status' => 'disabled']);
                }
            }
        }
    }
}
