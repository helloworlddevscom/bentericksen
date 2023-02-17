<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\TimeOff;
use App\User;
use Auth;
use Bentericksen\ViewAs\ViewAs;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

/**
 * Class DashboardController.
 *
 * Controller for the "User" (e.g., owner/manager) dashboard.
 *
 * For the "regular employee" dashboard, @see \App\Http\Controllers\Employee\DashboardController
 */
class DashboardController extends Controller
{
    private $user;

    private $businessId;

    private $businessSettings;

    public function __construct(ViewAs $viewAs)
    {
        $this->user = $viewAs->getUser();
        $this->businessId = $this->user->business_id;
        $this->businessSettings = $this->user->business->getSettings();
    }

    /**
     * Displays the owner/manager dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::where([
            'business_id' => $this->businessId,
            'terminated' => null,
        ])->get();

        $anniversaries = [];
        $birthdays = [];
        $timeOffRequests = [];
        $leaveOfAbsence = [];
        $ids = [];

        $cutoff_date = Carbon::today()->addDays($this->businessSettings->dashboard_reminders_days);

        foreach ($users as $key => $user) {
            array_push($ids, $user->id);

            $users[$key]->paperwork = DB::table('paperwork')
                ->whereNotIn('id', DB::table('users_paperwork')->where('user_id', $user->id)->pluck('paperwork_id'))
                ->get();

            $users[$key]->job_description = 1;
            $job = DB::table('user_job_description')->where('user_id', $user->id)->first();
            if (is_null($job)) {
                $users[$key]->job_description = 0;
            }

            // Get user's licensure/certifications
            $certifications = DB::table('user_licensure_certifications')
                ->join('licensure_certifications', 'licensure_certifications.id', '=', 'user_licensure_certifications.licensure_certifications_id')
                ->where('user_id', $user->id)
                ->whereNotNull('expiration')
                ->where('expiration', '!=', '0000-00-00 00:00:00')
                ->whereDate('expiration', '<', $cutoff_date)
                ->orderBy('expiration', 'desc')
                ->get();

            // ... and their driver's license (adding a "name" field)
            $drivers_license = DB::table('driver_licenses')
                ->where('user_id', $user->id)
                ->whereNotNull('expiration')
                ->where('expiration', '!=', '0000-00-00 00:00:00')
                ->whereDate('expiration', '<', $cutoff_date)
                ->orderBy('expiration', 'desc')
                ->get()
                ->map(function ($item) {
                    $item->name = 'Driver';

                    return $item;
                });

            // ... and their driver's insurance (adding a "name" field)
            $drivers_insurance = DB::table('driver_licenses')
                ->where('user_id', $user->id)
                ->whereNotNull('policy_expiration')
                ->where('policy_expiration', '!=', '0000-00-00 00:00:00')
                ->whereDate('policy_expiration', '<', $cutoff_date)
                ->orderBy('policy_expiration', 'desc')
                ->get()
                ->map(function ($item) {
                    $item->name = 'Car Ins.';
                    $item->expiration = $item->policy_expiration;

                    return $item;
                });

            // ... and merge them, re-sorted
            $users[$key]->licenses = $certifications
                ->merge($drivers_license)
                ->merge($drivers_insurance)
                ->sortByDesc('expiration');

            // add to birthdays or anniversaries lists, if appropriate
            // keys for these arrays need to prevent collisions if 2 employees are on the same day, and also sort by name
            $anniv = $user->getNextAnniversary();
            if (! empty($anniv) && $anniv->lessThan($cutoff_date)) {
                $key = $anniv->timestamp.'.'.$user->last_name.$user->first_name.$user->id;
                $anniversaries[$key] = $user;
            }
            $bday = $user->getNextBirthday();
            if (! empty($bday) && $bday->lessThan($cutoff_date)) {
                $key = $bday->timestamp.'.'.$user->last_name.$user->first_name.$user->id;
                $birthdays[$key] = $user;
            }
        }

        $asaExpiration = $this->getAsaExpirationNotice(\Request::instance());

        $this->parseTimeOffRequests($ids, $timeOffRequests, $leaveOfAbsence);

        ksort($anniversaries);
        ksort($birthdays);

        return view('user.index', compact([
            'users',
            'birthdays',
            'anniversaries',
            'timeOffRequests',
            'leaveOfAbsence',
            'asaExpiration',
        ]));
    }

    /**
     * Calculating Time Difference between ASA Expiration and today's date.
     */
    public function getAsaExpirationNotice(Request $request)
    {
        $todaysDate = Carbon::today();
        $expirationDate = DB::table('business_asas')
        ->select('expiration')
        ->where('business_id', $this->businessId)
        ->get();

        $asaExpiration = Carbon::parse(($expirationDate[0]->expiration), 'UTC');

        $days = date_diff($todaysDate, $asaExpiration)->format('%a');

        if ($days <= 30) {
            $asaExpiration = $asaExpiration->toFormattedDateString();

            return $asaExpiration;
        }
    }

    /**
     * Accepting license agreement.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function licenseAgreementAccept()
    {
        $userId = $this->user->id;
        $date = Carbon::now();

        DB::Statement('UPDATE users SET license_agreement_accepted_at = "'.$date.'" WHERE id = '.$userId);

        return redirect()->route('user.dashboard');
    }

    /**
     * Disables employee count reminder for remainder of session.
     */
    public function employeeCountReminderSessionIgnore(): void {
        session()->put('ignoreEmployeeCountReminder', true);
    }

    /**
     * Parsing Time off requests to build Time Off Requests and Leave of Absence
     * Dashboard blocks.
     *
     * @param $ids
     * @param $timeOffRequests
     * @param $leaveOfAbsence
     */
    private function parseTimeOffRequests($ids, &$timeOffRequests, &$leaveOfAbsence)
    {
        // getting all the requests
        $requests = TimeOff::whereIn('user_id', $ids)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($requests as $timeoff) {
            // do not display if request is in the past or if request is not pending.
            if (strtotime($timeoff->end_at) < time() || $timeoff->status != 'pending') {
                continue;
            }

            $key = strtotime($timeoff->created_at);
            $type = $timeoff->request_type == 'leave' ? 'leaveOfAbsence' : 'timeOffRequests';

            if (array_key_exists($key, ${$type})) {
                $key += 1;
            }

            ${$type}[$key] = $timeoff;
        }
    }
}
