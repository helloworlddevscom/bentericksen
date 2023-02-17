<?php

namespace App\Console\Commands;

use App\Business;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class CreateMissingBusinessASA extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createMissingBusinessASA
                            {businessId : the ID of the business that\'s missing an ASA}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates an ASA record for a business.';

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
        $businessId = $this->argument('businessId');
        $business = Business::where('id', $businessId)->first();

        if(!is_null($business->asa))
        {
            $this->line('Business already has an asa.  Exiting.');
            return 0;
        }

        $type = $this->choice(
            'ASA Type?',
            [
                'limited',
                'basic',
                'comprehensive',
                'monthly',
                'annual-1-14',
                'annual-15-30',
                'annual-31-49',
                'annual-50-99',
                'annual-100-249',
                'annual-250-499',
                'annual-500']
        );
        $expiration = $this->ask('Expiration Date? (YYYY-MM-DD)');

        if($this->confirm(
            sprintf('Creating ASA for business id %d, with type %s and expiration %s.  Continue?',
                $businessId,
                $type,
                $expiration)
        ))
        {
            $asa = [
                'business_id' => $businessId,
                'type' => $type,
                'expiration' => $expiration,
                'status' => 'active'
            ];

            $result = DB::table('business_asas')->insert($asa);
            if(!$result) {
                $this->line('Error creating ASA record.');
            } else {
                $this->line('ASA created.');
            }
            return 0;
        }
    }
}
