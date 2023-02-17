<?php

namespace App\Mail;

use App\User;
use Illuminate\Mail\Mailable;

/**
 * Class PasswordResetEmail.
 *
 * Mailable class for creating mailable object response for password reset events
 */
class PasswordResetEmail extends Mailable
{
    /**
     * Value of user id.
     * @var string
     */
    public $user_id;

    /**
     * Value of Controller Location firing event.
     * @var string
     */
    public $related_type;

    /**
     * Response from sendResetLink broker.
     * @see \Illuminate\Auth\Passwords\PasswordBroker
     * @var string
     */
    public $response;

    /**
     * Value to record in DB of email send status.
     * @var string (sent, error)
     */
    public $status;

    /**
     * If Error, human readable explanation of error.
     * @var string
     */
    public $error;

    /**
     * Create a new message instance for logging purposes.
     *
     * @return void
     * @var array
     */
    public function __construct($attributes)
    {
        $this->subject = $attributes['subject'];
        $this->user_id = $attributes['user_id'];
        $this->to = $attributes['to'];
        $this->related_type = $attributes['related_type'];
        $this->response = $attributes['response'];

        $this->buildEntry();
    }

    /**
     * Build the logging entry.
     *
     * @see \Illuminate\Auth\Passwords\PasswordBroker
     *
     * @return $this
     */
    public function buildEntry()
    {
        // Representing a successfully sent reminder
        if ($this->response == 'passwords.sent') {
            $this->status = 'sent';
            $this->error = '';
        }

        // Representing the user not found response.
        if ($this->response == 'passwords.user') {
            $this->status = 'error';
            $this->error = 'Error - No email sent - user not found';
        }

        //  Default case when unknown response status received.
        if ($this->response == null) {
            $this->status = 'error';
            $this->error = "'Error - Logging error unknown response code";
        }

        // Special case for password reset when not logged in.
        if ($this->user_id == null) {
            try {
                $user = User::where('email', $this->to)->first();
                $this->user_id = $user->id;
            } catch (\Exception $exc) {
                // If there was error when saving above, this records it in the DB
                $this->status = 'error';
                $this->error = 'Error - no email sent - unable to find user - '.$exc->getCode().': '.$exc->getMessage();
            }
        }
    }
}
