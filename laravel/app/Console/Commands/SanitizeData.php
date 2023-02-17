<?php

namespace App\Console\Commands;

use App\Business;
use App\BusinessAsas;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SanitizeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanitizeData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes business info from the database and sets businesses to "Do not Contact"';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // safeguards to prevent this from being run in prod
        $env = \Config::get('app.env');
        if (empty($env)) {
            $this->line('Please set APP_ENV environment variable to allow this script to run.');

            return;
        }
        if ($env == 'production') {
            $this->line('This script is not allowed to run in the production environment.');
            $this->line("(APP_ENV is set to 'production' in .env file. You may change this if you're running this from somewhere other than production)");

            return;
        }

        // set all businesses to "do not contact"
        $this->line("Setting the 'do not contact' flag on all businesses...");
        Business::whereNotIn('id', [1, 2])->where('name', 'not like', 'Update Process%')->update(['do_not_contact' => true]);

        $this->line('Sanitization operation complete!');
    }
}
