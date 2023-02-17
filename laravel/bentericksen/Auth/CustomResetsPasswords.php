<?php

namespace Bentericksen\Auth;

use App\Mail\PasswordResetEmail;
use App\OutgoingEmail;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;

trait CustomResetsPasswords
{

    use SendsPasswordResetEmails;

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('auth.password');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEmail(Request $request)
    {

        $this->validate(
            $request,
            ['email' => 'required|email|exists:users'],
            ['exists' => 'Whoops! Looks like we don\'t have that email on file. Please verify the spelling and contact our support team at (800) 679-2760.']
        );

        $input = $request->only('email');

        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

        // Create Mailable record to record password reset event status in outgoing_emails table.
        $attributes = [
            'subject' => "HR Director Password Reset",
            'user_id' => null,
            'to' => $input['email'],
            'related_type' => self::class,
            'response' => $response
        ];

        $mailable = new PasswordResetEmail($attributes);
        $mailer = new OutgoingEmail([], $mailable);
        $mailer->saveFromMailable();

        return redirect()->back()
            ->with('status', trans($response))
            ->withInput( ['email' => $request->input('email')] );
    }

}
