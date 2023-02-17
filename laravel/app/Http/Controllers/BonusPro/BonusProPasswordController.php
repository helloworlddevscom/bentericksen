<?php

namespace App\Http\Controllers\BonusPro;

use App\BonusPro\Plan;
use App\Http\Controllers\Controller;
use App\Mail\PasswordResetEmail;
use App\OutgoingEmail;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class BonusProPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use SendsPasswordResetEmails;

    public function showLinkRequestForm(Plan $plan)
    {
        $user = \Auth::user();

        $canSet = $user->hasRole('owner') || $user->hasRole('consultant') || $user->hasRole('admin');

        return view('auth.bonuspro.plan.password')->with([
            'plan' => $plan,
            'email' => $canSet ? $user->email : $plan->email,
        ]);
    }

    public function sendResetLinkEmail(Request $request, Plan $plan)
    {
        $user = \Auth::user();

        $email = $plan->user->email;

        $canSet = $user->hasRole('owner') || $user->hasRole('consultant') || $user->hasRole('admin');

        if ($canSet) {
            $plan->reset_by = $user->id;
            $email = $user->email;
        } else {
            $plan->reset_by = $plan->user->id;
        }

        $plan->save();

        $response = $this->broker()->sendResetLink(['id' => $plan->id]);

        // Create Mailable record to record password reset event status in outgoing_emails table.
        $attributes = [
            'subject' => 'BonusPro Password Reset',
            'user_id' => $plan->reset_by,
            'to' => $email,
            'related_type' => self::class,
            'response' => $response,
        ];

        $mailable = new PasswordResetEmail($attributes);
        $mailer = new OutgoingEmail([], $mailable);
        $mailer->saveFromMailable();

        return redirect()->back()
            ->with('status', trans($response))
            ->withInput(['email' => $email]);
    }

    protected function broker()
    {
        return Password::broker('bonuspro-plans');
    }
}
