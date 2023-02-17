<?php

namespace Bentericksen\Employees;

use Illuminate\Support\Str;
use App\EmergencyContact;
use App\Salary;
use App\User as UserModel;
use Bentericksen\ActivateAccount\ActivateAccount;
use DB;

class CreateEmployee
{

    /**
     * Required Fields for CSV import
     *
     * @var array
     */
    protected $required = [
        'email',
        'first_name',
        'last_name',
    ];

    /**
     * Correct names for Import
     *
     * @var array
     */
    protected $rename = [
        "address_1" => "address1",
        "address_2" => "address2",
        "zipcode" => "postal_code",
        "phone1_number" => "phone1",
        "phone2_number" => "phone2",
        "date_of_birth" => "dob",
        'salary_amount' => 'salary',
        'salary_per' => 'rate',
    ];

    /**
     * @var array
     */
    protected $actionAndRemove = [
        "send_activation_email" => "Do this Action",
    ];

    /**
     * Creates Employee
     *
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $business_id = null;

        // @todo: Implement Exception Handler
        if (!isset($data['business_id']) || is_null($data['business_id']) || !is_int($data['business_id'])) {
            dd('failed');
        }

        $business_id = $data['business_id'];

        $affirmative = [
            'yes',
            'true',
            '1',
        ];

        $skipped = [];
        $imported = [];

        foreach ($data['employees'] as $employee) {
            if ($this->checkRequiredFields($employee) !== true) {
                $skipped[$employee['email']] = 'failed required fields validation';
                continue;
            }

            if ($this->isEmailUnique($employee['email']) === false) {
                $skipped[$employee['email']] = 'email is not unique';
                continue;
            }

            if (!empty($employee['phone1_number'])) {
                $employee['phone1_number'] = $this->formatPhoneNumber($employee['phone1_number']);
            }

            if (!empty($employee['phone2_number'])) {
                $employee['phone2_number'] = $this->formatPhoneNumber($employee['phone2_number']);
            }

            $employee['business_id'] = $business_id;
            $employee['password'] = bcrypt(Str::random(40));
            $employee = $this->remap($employee);

            $send_email = $employee['send_activation_email'];

            if (empty($employee['salary'])) {
                $employee['salary'] = '0.0';
            }

            if (empty($employee['rate'])) {
                $employee['rate'] = 'hour';
            }

            $salary_data = [
                'salary' => $employee['salary'],
                'rate' => $employee['rate'],
            ];

            unset($employee['send_activation_email']);
            unset($employee['salary']);
            unset($employee['rate']);
            unset($employee['']);

            $user = UserModel::create($employee);
            $user->roles()->attach(5);

            $this->createDriversLicense($user);
            $this->createEmergencyContact($user);
            $this->createSalary($user, $salary_data);


            if (in_array(strtolower($send_email), $affirmative)) {
                $this->activateAccount = new ActivateAccount($user->id);
            }

            $imported[] = $employee['email'];
        }

        return [
            'skipped' => $skipped,
            'imported' => $imported,
        ];
    }

    /**
     * Create Emergency Contact Record for Employee
     *
     * @param $user
     */
    private function createEmergencyContact($user)
    {
        for ($i = 0; $i < 2; $i++) {
            $emergencyContact = new EmergencyContact;
            $emergencyContact->user_id = $user->id;
            $emergencyContact->name = " ";
            $emergencyContact->phone1 = " ";
            $emergencyContact->phone1_type = "cell";
            $emergencyContact->phone2 = " ";
            $emergencyContact->phone2_type = "cell";
            $emergencyContact->phone3 = " ";
            $emergencyContact->phone3_type = "cell";
            $emergencyContact->relationship = "";
            $emergencyContact->is_primary = ($i == 0 ? 1 : 0);
            $emergencyContact->save();
        }
    }

    /**
     * Creates Salary record.
     *
     * @param $user
     * @param $salary_data
     *
     * @return Salary
     */
    private function createSalary($user, $salary_data)
    {
        $salary = new Salary;
        $salary->user_id = $user->id;
        $salary->salary = !empty($salary_data['salary']) ? $salary_data['salary'] : "0.00";
        $salary->rate = !empty($salary_data['rate']) ? $salary_data['rate'] : "hour";
        $salary->save();

        return $salary;
    }


    /**
     * Creates DL record.
     *
     * @todo: Revise
     *
     * @param $user
     */
    private function createDriversLicense($user)
    {
        $license = DB::table('driver_licenses')
            ->where('user_id', $user->id)
            ->first();

        if (!is_null($license)) {
            $license = DB::table('driver_licenses')
                ->where('user_id', $user->id)
                ->delete();
        }

        DB::table('driver_licenses')->insert(['user_id' => $user->id]);
    }

    /**
     * @param $employee
     *
     * @return mixed
     */
    private function remap($employee)
    {
        foreach ($this->rename as $key => $value) {
            if (!array_key_exists($key, $employee)) {
                continue;
            }
            $employee[$value] = $employee[$key];
            unset($employee[$key]);
        }

        return $employee;
    }

    /**
     * Verifies if employee already exists in the system (by email)
     *
     * @param $email
     *
     * @return bool
     */
    private function isEmailUnique($email)
    {
        $user = UserModel::where('email', $email)->first();

        return is_null($user);
    }

    /**
     * Returns false if any required field is missing from record. Checks also
     * if email is valid.
     *
     * @param $employee
     *
     * @return bool
     */
    private function checkRequiredFields($employee)
    {
        foreach ($this->required as $required) {
            if (is_null($employee[$required]) || $employee[$required] === "") {
                return false;
            }

            if ($required === "email") {
                if (!filter_var($employee['email'], FILTER_VALIDATE_EMAIL)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Parsing phone number to the correct format.
     *
     * @param  string $value phone number
     *
     * @return mixed          null if invalid number
     */
    protected function formatPhoneNumber($value)
    {
        // removing all non-numeric characters
        $value = preg_replace("/[^0-9]/", "", $value);

        // if count is less than 10 characters, it is an invalid number
        if (strlen($value) != 10) {
            return null;
        }

        if (preg_match('/^(\d{3})(\d{3})(\d{4})$/', $value, $matches)) {
            return '(' . $matches[1] . ') ' . $matches[2] . '-' . $matches[3];
        }
    }
}
