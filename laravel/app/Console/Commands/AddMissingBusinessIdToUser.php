<?php

namespace App\Console\Commands;

use App\Business;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class AddMissingBusinessIdToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addMissingBusinessIdToUser
                            {userId : the user id}
                            {businessId : the missing business id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds a business id to a user record.';

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
        $userId = $this->argument('userId');
        $businessId = $this->argument('businessId');
        $user = User::where('id', $userId)->first();

        if(!is_null($user->business_id))
        {
            $this->line('User already has a business id.  Exiting.');
            return 0;
        }

        if($this->confirm(
            sprintf('Adding business id %d to user %d.  Continue?',
                $businessId,
                $userId
            )
        ))
        {
            $user->business_id = $businessId;
            $result = $user->save();

            if(!$result) {
                $this->line('Error adding business id to user record.');
            } else {
                $this->line('Business id successfully added to user record.');
            }
            return 0;
        }
    }
}
