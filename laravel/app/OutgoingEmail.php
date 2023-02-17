<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OutgoingEmail.
 *
 * Wrapper for emails sent by the application. This saves emails to the DB for
 * tracking purposes.
 *
 * Usage example (with user account):
 *
 * ```
 * // first, we create a mailable object and pass in the required variables
 * $mailable = new PendingPolicyReview(\Auth::user(), $this->business, $policy);
 * // then we create the mailer
 * $mailer = new OutgoingEmail([], $mailable);
 * // the user_id will determine the email address it goes to
 * $mailer->user_id = 42;
 * // we can optionally attach a related object to the email
 * $mailer->related_type = 'Policy';  // this must be a model class name
 * $mailer->related_id = '99';  // this is the ID of the related object
 * // then we send it, which automatically sets the sent_at and status properties
 * // and saves it to the DB
 * $mail->send();
 * ```
 *
 * Usage example (without user account, using a string email address instead):
 *
 * ```
 * $mailable = new PendingPolicyReview(\Auth::user(), $this->business, $policy);
 * $mail = new OutgoingEmail([], $mailable);
 * // here, we pass in the recipient email address as a string
 * $mail->to_address = 'recipient@example.com';
 * $mail->send();
 * ```
 *
 * @property int $id The auto-increment ID
 * @property int $user_id The ID of the user the email was sent to (may be null if sending to a non-user)
 * @property \App\User $user The user object
 * @property string $type The name of the Mailable class used to generate the email (a class name from \App\Mail)
 * @property string $subject The email subject
 * @property string $body The generated body
 * @property string $to_address The email address that appears in the 'to' field
 * @property int $related_id The ID of the related object (a polymorphic relationship)
 * @property int $related_type The type of related object (e.g., "UserPolicyUpdate")
 * @property int $sent_at The time the email was sent
 * @property string $status The status, e.g., 'sent', 'error'
 * @property string $error The error message, if sending failed
 * @property int $created_at
 * @property int $updated_at
 * @property string $config_value the value of the config APP_ENV.
 * @property $related The related object, of type PolicyUpdater or possibly others
 */
class OutgoingEmail extends Model
{
    protected $table = 'outgoing_emails';
    protected $fillable = [];
    protected $guarded = [];

    /**
     * @var \Illuminate\Mail\Mailable The instance of a Mailable object which will be used to render the email template
     */
    public $mailable;

    /**
     * User Eloquent relationship.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Reference relationship (polymorphic)
     * Relates this model to another (non-user) model, like a Policy or PolicyUpdater.
     */
    public function related()
    {
        return $this->morphTo();
    }

    /**
     * Create a new mailer instance.
     *
     * @param array $attributes
     * @param \Illuminate\Mail\Mailable|null $mailable The Mailable object. These are found in laravel\app\Mail
     * and must be instantiated with all the parameters that will be put into the view.
     *
     * @return void
     */
    public function __construct($attributes = [], \Illuminate\Mail\Mailable $mailable = null)
    {
        parent::__construct($attributes);
        $this->mailable = $mailable;
    }

    /**
     * Sends an email.
     */
    public function send()
    {
        $this->sent_at = Carbon::now()->format('Y-m-d H:i:s');
        // Recipient address comes from the user account, if there is one.
        // If there isn't a user account, the address needs to be set in the $to_address property.
        if ($this->user_id && $this->user) {
            $this->to_address = $this->user->email;
        }
        $classname = get_class($this->mailable);
        $this->type = substr($classname, strrpos($classname, '\\') + 1); // class name without namespace

        // we use the Mailable class to render the body so we can save it in the DB
        $this->subject = $this->mailable->subject;
        $this->body = $this->mailable->render();
        $this->status = 'queued';
        $this->save();

        if (empty($this->to_address)) {
            // if we didn't find a user above, and the "to" address isn't set, we can't send it.
            $this->status = 'error';
            $this->error = 'Invalid recipient email address';
            $this->save();

            return;
        }

        // In non-production mode, we record the email as a "test" and don't send it.
        // (Unless the recipient email address is one of the domains used for testing.)

        if (config('app.env') != 'production'
            && stripos($this->to_address, 'bentericksen.com') === false
            && stripos($this->to_address, 'helloworlddevs.com') === false
        ) {
            $this->status = 'test';
            $this->error = 'Development mode. Emails are recorded, but not sent.';
            $this->save();

            return;
        }

        // add in config value to all emails
        $this->config_value = config('app.env');

        // when in production (or using one of the test domains above, we actually send the email
        try {
            \Mail::to($this->to_address)->send($this->mailable);
            $this->status = 'sent';
            $this->save();
        } catch (\Exception $exc) {
            // If there was an SMTP error when sending above, this records it in the DB
            $this->status = 'error';
            $this->error = $exc->getCode().': '.$exc->getMessage();
            $this->save();
        }
    }

    /**
     * Logs an email for action outside normal operations.
     *
     * This includes password resets and account activations, but a general function
     * for any future emails which want to be logged.
     *
     * This may be a duplicate of an email sent in another table, but outgoing_emails is
     * defined as the location for all emails sent to be viewed from.
     */
    public function saveFromMailable()
    {
        $this->sent_at = Carbon::now()->format('Y-m-d H:i:s');
        // Set attributes for mailer
        $classname = get_class($this->mailable);
        $this->type = substr($classname, strrpos($classname, '\\') + 1); // class name without namespace

        $this->config_value = config('app.env');

        // we use the Mailable class to render the body so we can save it in the DB
        $this->subject = $this->mailable->subject;
        $this->to_address = $this->mailable->to;
        $this->user_id = $this->mailable->user_id;
        $this->related_type = $this->mailable->related_type;
        $this->status = $this->mailable->status;
        $this->error = $this->mailable->error;

        //  Default blank.   No relationship at this time
        $this->related_id = '';
        $this->save();
    }
}
