<?php

namespace App\Service;

use Illuminate\Support\Facades\Session;

class EmployeeCountReminder {

    /**
     * @return bool
     */
    public static function employeeCountReminderSessionIgnoreStatus() {
        if(session()->has('ignoreEmployeeCountReminder')) {
            return session()->get('ignoreEmployeeCountReminder');
        }
        return false;
    }

    /**
     * @param \App\Business $business
     * @return bool
     */
    public static function displayEmployeeReminder(\App\Business $business): bool {
        return !self::employeeCountReminderSessionIgnoreStatus()
            && !$business->employee_count_reminder;
    }

}
