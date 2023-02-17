<?php

namespace Bentericksen\ActivateAccount;

use App\Mail\AccountActivationEmail;
use App\OutgoingEmail;
use Bentericksen\Employees\User as Users;

use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;

class ActivateAccount
{

    protected $email;
    private $user;
    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        $this->user = new Users($userId);
        $this->email = $this->user->getEmail();

        $this->sendEmail($this->email);
    }

    public function sendEmail($email)
    {
        $response = Password::sendResetLink(['email' => $email], function (Message $message) {
            $message->subject('Account Activation');
        });

        // Create Mailable record to create account activation event in outgoing_emails table.
        $attributes = [
            'subject' => "Account Activation Email",
            'user_id' => $this->userId,
            'to' => $email,
            'related_type' => self::class,
            'response' => $response
        ];

        $mailable = new AccountActivationEmail($attributes);
        $mailer = new OutgoingEmail([], $mailable);
        $mailer->saveFromMailable();

    }
}