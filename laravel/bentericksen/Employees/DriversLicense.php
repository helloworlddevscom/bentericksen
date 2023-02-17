<?php

namespace Bentericksen\Employees;

use Carbon\Carbon;
use DB;
use Bentericksen\Settings\States;
use App\DriversLicense as License;
use Bentericksen\History\History;

class DriversLicense
{
    private $license;
    public $states;

    public $key_values = [
        'expiration' => 'Expiration',
        'agent' => 'Agent',
        'agent_phone' => 'Agent Phone',
        'policy_number' => 'Policy Number',
        'policy_expiration' => 'Policy Expiration',
    ];

    public function __construct($userId)
    {
        $this->license = License::where('user_id', $userId)->first();

        if (is_null($this->license)) {
            $this->license = new License;
            $this->license->user_id = $userId;
            $this->license->save();
        }

        $this->userId = $userId;

        $this->states = new States;

        $this->history = new History;
    }

    public function getExpiration()
    {
        if (!is_null($this->license->expiration)) {
            return date("m-d-Y", strtotime($this->license->expiration));
        }

        return null;
    }

    public function getAgent()
    {
        if (!is_null($this->license->agent)) {
            return $this->license->agent;
        }

        return '';
    }

    public function getAgentPhone()
    {
        if (!is_null($this->license->agent_phone)) {
            return $this->license->agent_phone;
        }

        return '';
    }

    public function getPolicyNumber()
    {
        if (!is_null($this->license->policy_number)) {
            return $this->license->policy_number;
        }

        return '';
    }

    public function getPolicyExpiration()
    {
        if (!is_null($this->license->policy_expiration)) {
            return date("m-d-Y", strtotime($this->license->policy_expiration));
        }

        return null;
    }

    public function save($data)
    {
        $values = [
            "id" => $this->license->id,
            "user_id" => $this->license->user_id,
            "expiration" => (isset($data["driver_license_expiration_date"]) && $data["driver_license_expiration_date"] != "" ? Carbon::createFromFormat('m/d/Y', $data["driver_license_expiration_date"])->format('Y-m-d') . " 00:00:00" : "0000-00-00 00:00:00"),
            "agent" => $data["driver_insurance_name"] !== '' ? $data["driver_insurance_name"] : null,
            "agent_phone" => $data["driver_insurance_phone_number"] !== '' ? $data["driver_insurance_phone_number"] : null,
            "policy_number" => $data["driver_insurance_policy_number"] !== '' ? $data["driver_insurance_policy_number"] : null,
            "policy_expiration" => (isset($data["driver_insurance_expiration_date"]) && $data["driver_insurance_expiration_date"] != "" ? Carbon::createFromFormat('m/d/Y', $data["driver_insurance_expiration_date"])->format('Y-m-d') . " 00:00:00" : "0000-00-00 00:00:00"),
        ];

        $oldData = [];

        foreach ($this->license->toArray() as $key => $field) {
            foreach ($values as $valueKey => $valueValue) {
                if ($key === $valueKey && ($field !== $valueValue)) {
                    $oldData[$key] = $field;
                }
            }
        }

        $note = 'Driver\'s License Updated';

        unset($oldData['created_at']);
        unset($oldData['updated_at']);

        foreach ($oldData as $key => $oldValue) {
            $note .= ' | ';
            $note .= ucfirst($this->key_values[$key]) . ' changed from ' . $oldValue . ' to ' . $values[$key] . '. ';
        }

        $this->history->add([
            'user_id' => $this->userId,
            'type' => 'Driver\'s License',
            'created_at' => (new Carbon)->format('Y-m-d h:i:s'),
            'note' => $note,
            'status' => 'active',
        ]);

        $this->history->save();
        $this->license->update($values);
    }
}