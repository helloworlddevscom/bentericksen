<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OutputConfigValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cfgout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Output Configuration Values';

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
        $this->info(config_path());
        $this->info(config('app.env'));
    }
}
