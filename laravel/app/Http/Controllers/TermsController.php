<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function index()
    {
        return view('terms.index')->with('modal_type', session('modal_type'));
    }

    /**
     * Accepting the Terms and Conditions.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function accept(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $user->accepted_terms = $now->toDateTimeString();
        $user->save();

        $user->business->updateStatus('active');
        session()->forget('modal_type');

        return redirect('/');
    }
}
