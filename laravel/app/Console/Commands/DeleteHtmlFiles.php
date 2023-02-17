<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use File;
use Illuminate\Console\Command;

class DeleteHtmlFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deleteHtmlFiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unnecessary HTML files';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = File::glob('/var/www/laravel/storage/app/*.html');

        foreach ($files as $file) {
            File::delete($file);
        }
    }
}
