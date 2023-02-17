<?php

namespace Bentericksen\Employees;

use App\Attendance as AttendanceModel;
use App\User;
use Carbon\Carbon;

class Attendance
{

    private $userId;

    private $user;

    private $statuses = [
        "blank" => "",
        "late" => "Late",
        "absent" => "Absent",
        "left_early" => "Left Early",
        "no_call_no_show" => "No Call No Show",
    ];

    public function __construct($userId)
    {
        $this->userId = $userId;
        $this->user = User::findOrFail($userId);
    }

    public function save($request)
    {
        if ($request['attendance_start_date'] !== '' &&
            $request['attendance_end_date'] !== '' &&
            $request['attendance_status'] !== '') {
            $data = [
                'user_id' => $this->userId,
                'note' => $request['attendance_note'],
            ];

            foreach ($this->statuses as $key => $value) {
                if ($key === $request['attendance_status']) {
                    $request['attendance_status'] = $value;
                }
            }

            if (!empty($request['attendance_status'])) {
                $data['status'] = $request['attendance_status'];
            }

            if (!empty($request['attendance_start_date'])) {
                $data['start_date'] = Carbon::createFromFormat('m/d/Y', $request['attendance_start_date'])
                        ->format('Y-m-d') . " 00:00:00";
            }

            if (!empty($request['attendance_end_date'])) {
                $data['end_date'] = Carbon::createFromFormat('m/d/Y', $request['attendance_end_date'])
                        ->format('Y-m-d') . " 00:00:00";
            }

            if (isset($data['end_date']) && isset($data['start_date'])) {
                AttendanceModel::create($data);
            }

            $history = new History($this->userId);

            $historyData = [
                'user_id' => $this->user->id,
                'business_id' => $this->user->business_id,
                'type' => 'attendance',
                'note' => $request['attendance_start_date'] . ' - ' .
                    $request['attendance_end_date'] . ' | ' .
                    ucfirst($data['status']) . ' | ' . $data['note'],
                'status' => 'active',
            ];

            $history->save($historyData);
        }
    }

    public function getAttendance()
    {
        $attendance = AttendanceModel::where('user_id', $this->userId)
            ->orderBy('id', 'desc')
            ->get();

        return $attendance;
    }
}
