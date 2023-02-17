<?php

namespace App\Console\Commands;

use App\SalaryUpdates;
use Illuminate\Console\Command;

class ChangeSalary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changeSalary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Change the salary of a user, if today's date matches the effective date";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $salaryUpdates = SalaryUpdates::where('effective_at', '<=', now())->get();

        foreach ($salaryUpdates as $update) {
            $user = $update->user;
            $user = [
                'salary' => $update->salary,
                'rate' => $update->rate,
                'effective_at' => $update->effective_at,
                'salary_updated' => now(),
                'reason' => $update->reason,
            ];

            if ($user->salary) {
                $user->salary->update($data);
            } else {
                $user->salary->create($data);
            }

            $update->delete();
        }
    }
}
