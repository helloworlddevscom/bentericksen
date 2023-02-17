<?php

namespace Bentericksen\Employees;

use \App\Salary as SalaryModel;
use App\User as UserModel;
use DB;
use Bentericksen\History\History;
use Carbon\Carbon;

class Salary
{

    /**
     * @var mixed|string
     */
    public $salary;

    /**
     * @var mixed|string
     */
    private $userId;

    /**
     * @var mixed|string
     */
    public $rate;


    private $user;

    /**
     * @var array
     */
    private $salaryFields = [
        'salary' => 'Salary',
        'rate' => 'Rate',
        'effective_at' => 'Effective Date',
        'reason' => 'Reason',
    ];

    /**
     * @var History
     */
    private $history;

    /**
     * @var SalaryModel
     */
    private $Salary;

    public function __construct($userId)
    {
        $this->userId = $userId;
        $this->user = UserModel::find($userId);

        $Salary = SalaryModel::where('user_id', $userId)->first();

        if (is_null($Salary)) {
            $Salary = new SalaryModel;
            $Salary->user_id = $userId;
            $Salary->salary = '0.00';
            $Salary->rate = 'hour';
            $Salary->effective_at = '0000-00-00 00:00:00';
            $Salary->reason = '';
            $Salary->salary_updated = 1;
            $Salary->save();
        }

        $this->Salary = $Salary;

        if (!is_null($Salary)) {
            $this->salary = $Salary->salary;
            $this->rate = $Salary->rate;
        }

        $this->history = new History;
    }

    public function save($request)
    {
        if (is_null($this->Salary)) {
            $this->Salary = new SalaryModel;
        }

        if (isset($request['salary']) && $request['salary'] !== '') {
            $salary = (float)str_replace(',', '', $request['salary']);
        } else {
            $salary = !empty($request['current_salary']) ? $request['current_salary'] : '0.00';
        }


        if (isset($request['salary_rate'])) {
            $salary_rate = $request['salary_rate'];
        } else {
            $salary_rate = !empty($request['current_rate']) ? $request['current_rate'] : null;
        }

        $effective = isset($request['effective_at']) ?
            Carbon::createFromTimestamp(strtotime($request['effective_at'])) : Carbon::now();
        $now = Carbon::now();

        $immediateUpdate = false;

        if (($this->Salary->salary === '0.00' && $this->Salary->updated_at <= '0000-00-00 00:00:00')
            || ($now->gte($effective) == true)) {
            $immediateUpdate = true;
        }

        $this->Salary->salary = $salary;
        $this->Salary->rate = $salary_rate;

        // if Salary or rate haven't changed, skip update.
        if (!$this->Salary->isDirty()) {
            return;
        }

        $this->Salary->effective_at = $effective;
        $this->Salary->user_id = $this->userId;
        $this->Salary->reason = isset($request['reason']) ? $request['reason'] : '';
        $this->Salary->salary_updated = $now;

        $originalData = $this->Salary->getOriginal();
        $newData = $this->Salary->toArray();

        $changes = array_diff_assoc($originalData, $newData);

        if (!array_key_exists('salary', $changes)) {
            $changes['salary'] = $this->Salary->salary;
        }

        foreach ($changes as $key => $change) {
            $changes[$key] = [
                'before' => $this->Salary->getOriginal($key),
                'after' => $this->Salary->{$key},
            ];
        }

        $this->Salary->save();

        if ($immediateUpdate) {
            DB::table('users')
                ->where('id', $this->userId)
                ->update([
                    'salary' => $this->Salary->salary,
                    'salary_rate' => $this->Salary->rate,
                ]);
        }

        $salaryArray = [
            'salary' => $this->Salary->salary,
            'rate' => $this->Salary->rate,
            'effective_at' => $this->Salary->effective_at,
            'reason' => $this->Salary->reason,
        ];

        if (isset($changes['rate']['before'])) {
            $salaryArray['beforeRate'] = $changes['rate']['before'];
        } else {
            $salaryArray['beforeRate'] = $salaryArray['rate'];
        }

        $data = [
            'user_id' => $this->userId,
            'business_id' => $this->user->business_id,
            'created_at' => (new Carbon)->format('Y-m-d h:i:s'),
            'status' => 'active',
            'type' => 'Salary',
            'note' => (new Carbon($salaryArray['effective_at']))->format('m/d/Y') .
                ' | Salary changed from $' . number_format($changes['salary']['before'], 2, '.', ',') .
                ' per ' . $salaryArray['beforeRate'] . ' to $' . number_format($salaryArray['salary'], 2, '.', ',') .
                ' per ' . $salaryArray['rate'],
        ];

        if (!empty($salaryArray['reason'])) {
            $data['note'] .= ' | ' . $salaryArray['reason'];
        }

        $this->history->add($data);
        $this->history->save();
    }

    /**
     * Getter
     * @return mixed|string
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * Getter
     * @return mixed|string
     */
    public function getRate()
    {
        return $this->rate;
    }
}
