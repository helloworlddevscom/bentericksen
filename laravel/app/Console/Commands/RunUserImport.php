<?php

namespace App\Console\Commands;

use App\Business;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class RunUserImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:bent-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute initial User Migration';

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $manuals_source_directory;

    /**
     * @var string
     */
    private $manuals_dest_directory;

    /**
     * @var array
     */
    private $businessColumns = [
        'name',
        'address1',
        'address2',
        'city',
        'state',
        'postal_code',
        'phone1',
        'phone1_type',
        'phone2',
        'phone2_type',
        'phone3',
        'phone3_type',
        'fax',
        'primary_role',
        'type',
        'subtype',
        'status',
    ];

    /**
     * @var array
     */
    private $userColumns = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'prefix',
        'address1',
        'address2',
        'city',
        'state',
        'postal_code',
        'phone1',
        'phone1_type',
        'phone2',
        'phone2_type',
        'primary_user_id',
    ];

    /**
     * @var array
     */
    private $asaColumns = [
        'asa_type',
        'asa_expiration_date',
    ];

    private $lowerCaseColumn = [
        'phone1_type',
        'phone2_type',
        'phone3_type',
        'asa_type',
        'subtype',
        'primary_role',
        'status',
    ];

    private $phoneColumns = [
        'phone1',
        'phone2',
        'phone3',
        'fax',
    ];

    private $mandatory_fields = [
        'id',
        'name',
        'first_name',
        'last_name',
        'primary_user_id',
    ];

    /**
     * @var array
     */
    private $registered_emails;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->filename = 'bentericksen/user_migration/data.csv';
        $this->manuals_source_directory = 'user_migration/manuals';
        $this->manuals_dest_directory = 'policy';
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->registered_emails = User::all()->pluck('email')->toArray();

        // opening CSV file.
        $csv = Reader::createFromPath(storage_path($this->filename), 'r');

        try {
            $csv->setHeaderOffset(0);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        $bar = $this->output->createProgressBar($csv->count());
        foreach ($csv as $key => $row) {
            if (! $this->validateEntry($key, $row)) {
                $bar->advance();
                continue;
            }

            $business = $this->createBusiness($row);

            $this->createAsaRecord($business, $row);
            $this->createUser($business, $row);
            $this->copyManual($business, $row);

            $business->save();
            $bar->advance();
        }

        $bar->finish();
        $this->info(' # Done');
    }

    /**
     * Creating business.
     *
     * @param $row
     *
     * @return Business
     */
    protected function createBusiness($row)
    {
        $business_data = $this->parseData($row, 'business');

        $business = new Business($business_data);
        $business->save();

        return $business;
    }

    /**
     * Adding ASA record.
     *
     * @param $business
     * @param $row
     */
    protected function createAsaRecord(&$business, $row)
    {
        $asa_data = $this->parseData($row, 'asa');
        $asa_data['type'] = $asa_data['asa_type'];
        $asa_data['expiration'] = $asa_data['asa_expiration_date'];

        unset($asa_data['asa_type']);
        unset($asa_data['asa_expiration_date']);

        $asa = $business->asa()->create($asa_data);

        $business->asa_id = $asa->id;
    }

    /**
     * Adding user.
     *
     * @param $business
     * @param $row
     */
    protected function createUser(&$business, $row)
    {
        $user_data = $this->parseData($row, 'user');
        $user_data['email'] = $user_data['primary_user_id'];

        unset($user_data['primary_user_id']);

        // Entering a random password to be changed upon User's first login.
        $user_data['password'] = bcrypt(random_int(-999999999, 999999999));

        $user = $business->users()->create($user_data);
        $business->primary_user_id = $user->id;
    }

    /**
     * Data parser.
     *
     * @param $row
     * @param $type
     *
     * @return array
     */
    protected function parseData($row, $type)
    {
        $array = [];

        foreach ($row as $key => $value) {
            // if column is not required for entity
            if (! in_array($key, $this->{$type.'Columns'})) {
                continue;
            }

            // if column value needs to be lowercase
            if (in_array($key, $this->lowerCaseColumn) && $value !== '') {
                $value = strtolower($value);
            }

            // formatting phone numbers
            if (in_array($key, $this->phoneColumns) && $value !== '') {
                $this->parsePhoneNumberString($value);
            }

            $array[$key] = $value !== '' ? $value : null;
        }

        return $array;
    }

    /**
     * Formatting string as a phone number.
     *
     * @param $value
     */
    protected function parsePhoneNumberString(&$value)
    {
        $string = preg_replace('/[^0-9]/', '', $value);
        $value = preg_replace("/(\d{3})(\d{3})(\d{4})/", '($1) $2-$3', $string);
    }

    /**
     * @param $business
     * @param $row
     *
     * @return mixed
     */
    protected function copyManual(&$business, $row)
    {
        $filename = 'PolicyManual'.$row['id'].'.pdf';
        $source = $this->manuals_source_directory.'/'.$filename;
        $destination = $this->manuals_dest_directory.'/'.$filename;

        if (Storage::disk('bentericksen')->exists($source)) {
            if (! Storage::disk('bentericksen')->exists($destination)) {
                Storage::disk('bentericksen')->copy($source, $destination);
            }
            $business->manual = $filename;
        }
    }

    private function validateEntry($index, $row)
    {
        $valid = true;
        foreach ($row as $key => $value) {
            // checking mandatory fields
            if (in_array($key, $this->mandatory_fields) && empty($value)) {
                $msg = ' # Skipping row '.$index.'. Reason: Field '.$key.' is required.';
                $this->error($msg);
                $valid = false;
            }

            // checking if email already exists in the system.
            if ($key == 'primary_user_id' && in_array($value, $this->registered_emails)) {
                $msg = ' # Skipping row '.$index.'. Reason: Email '.$value.' already exists in the system.';
                $this->error($msg);
                $valid = false;
            }
        }

        return $valid;
    }
}
