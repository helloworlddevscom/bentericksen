<?php

namespace App\Console\Commands;

use App\Business;
use Illuminate\Console\Command;

class SetDoNotContact extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'business:no-contact';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all business settings to do not contact.';

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
     * @return mixed
     */
    public function handle()
    {
        $result = Business::where('do_not_contact', 0)
            ->update(['do_not_contact' => 1]);

        $this->info("Updated {$result} businesses.");
    }
}
