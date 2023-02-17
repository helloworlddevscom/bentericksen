<?php

namespace App\Console\Commands;

use App\Business;
use App\BusinessAsas;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Imports\PatchBusinessesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class PatchCancelledBusinesses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patchCancelledBusinesses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Patch canclled businesses and reset their statuses';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = storage_path('app/business-patch.xlsx');
        
        $data = Excel::toArray(new PatchBusinessesImport, $path);
 
        $data = array_slice(current($data), 1);
        
        $this->info('Records to import: '.count($data));

        $businesses = array_map(function($row) {
            return [$row[0], $row[11]];
        }, $data);
        
        $businesses = array_map(function($business) {
            $business[0] = preg_replace('/\s+$/', '', $business[0]);
            return $business;
        }, $businesses);

        $this->info('Businesses Parsed: '.count($businesses));

        $duplicates = collect($businesses)->duplicates(0);

        $businesses = collect($businesses)->unique(0);
        
        $this->info('Unique Businesses: '.count($businesses));

        $this->info('Duplicate Businesses: '.count($duplicates));
        
        $names = $businesses->pluck(0)->filter()->unique()->toArray();

        $this->info('Unique business names: '.count($names));

        $emails = $businesses->pluck(1)->filter()->unique()->toArray();

        $this->info('Unique emails: '.count($emails));
        
        $businessesByEmail = User::whereIn('email', $emails)->get()->pluck('business_id')->unique();
        $userEmails = User::whereIn('email', $emails)->get()->pluck('email')->unique();
        
        $businessesByName = Business::whereIn('name', $names)->get()->pluck('id')->unique();
        $businessNames = Business::whereIn('name', $names)->get()->pluck('name')->unique();
        
        $allBusinessIds = $businessesByEmail->merge($businessesByName)->unique();

        $uniqueBusinessesByName = $businessesByName->diff($businessesByEmail);

        $totalFound = count($allBusinessIds);

        $missing = count($businesses) - $totalFound;

        $this->info(sprintf('Businesses found by name: %s', count($businessesByName), count($uniqueBusinessesByName)));

        $this->info(sprintf('Businesses found only by name: %s', count($uniqueBusinessesByName)));

        $this->info('Businesses found by email: '.count($businessesByEmail));

        $this->info('Businesses found total: '.$totalFound);

        $this->info('Businesses not found: '.$missing);

        $notFound = $businesses
            ->reject(function($b) {
                return empty($b[0]) || empty($b[1]);
            })
            ->filter(function($b) use($userEmails, $businessNames) {
                return !$userEmails->contains($b[1]) && !$businessNames->contains($b[0]);
            })
            ->unique(0)
            ->unique(1);

        Storage::put('AccountsNotFound.txt', implode(PHP_EOL, $notFound->pluck(0)->toArray()));
        Storage::put('Duplicates.txt', implode(PHP_EOL, $duplicates->values()->toArray()));
        
        $this->info('Starting patch...');

        $affected = Business::whereIn('id', $allBusinessIds)->update([
            'status' => 'cancelled'
        ]);

        $this->info("$affected businesses affected");
    }
}
