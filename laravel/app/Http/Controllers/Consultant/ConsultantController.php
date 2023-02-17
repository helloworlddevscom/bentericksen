<?php

namespace App\Http\Controllers\Consultant;

use App\Business;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Bentericksen\ViewAs\ViewAs;
use Carbon\Carbon;

class ConsultantController extends Controller
{
    protected $consultant;

    public function __construct(ViewAs $viewAs)
    {
        $this->consultant = User::findOrFail($viewAs->getUserId());
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $active_businesses = Business::where('consultant_user_id', Auth::user()->id)->get();

        $businesses = [];
        foreach ($active_businesses as $business) {
            // if business is finalized, check if ongoing_consultant_cc flag is set. If not, skip it.
            if ($business->finalized && $business->ongoing_consultant_cc == 0) {
                continue;
            }

            $business->primary = User::find($business->primary_user_id);

            array_push($businesses, $business);
        }

        return view('consultant.index')
            ->with('businesses', $businesses)
            ->with('consultant', $this->consultant);
    }

    /**
     * Setting the viewAs variables.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function viewAs($id)
    {
        $business = Business::findOrFail($id);

        session()->put('viewAs', $business->primary_user_id);
        session()->put('viewAsRole', 'consultant');

        return redirect()->route('user.dashboard');
    }

    /**
     * Marking the License Agreement as accepted.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function licenseAgreementAccept()
    {
        $this->consultant->license_agreement_accepted_at = Carbon::now();
        $this->consultant->save();

        return redirect()->route('consultant.dashboard');
    }
}
