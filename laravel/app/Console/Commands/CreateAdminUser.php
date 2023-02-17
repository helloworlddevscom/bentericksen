<?php

namespace App\Console\Commands;

use App\Mail\AccountActivationEmail;
use App\OutgoingEmail;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createAdminUser 
    {--email= : Unique email for the new account.}
    {--first_name= : User\'s first name}
    {--last_name= : User\'s last name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates an Admin User for HR Director.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->option('email');
        $first_name = $this->option('first_name');
        $last_name = $this->option('last_name');
        $password = bcrypt(Str::random(12));

        if (empty($email)) {
            return $this->error('Email is required.');
        }
        if (! $this->isUnique($email)) {
            return $this->error('Email ('.$email.') already registered in the system.');
        }

        if (empty($first_name)) {
            return $this->error('First Name is required.');
        }

        if (empty($last_name)) {
            return $this->error('Last Name is required.');
        }

        /** @var User $user */
        $user = User::create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => $password,
        ]);

        $user->attachRole(1);

        $response = Password::sendResetLink(['email' => $email], function (Message $message) {
            $message->subject('Account Activation');
        });

        // Create Mailable record to record account activation event status in outgoing_emails table.
        $attributes = [
            'subject' => 'Account Activation Email',
            'user_id' => null,
            'to' => $email,
            'related_type' => self::class,
            'response' => $response,
        ];

        $mailable = new AccountActivationEmail($attributes);
        $mailer = new OutgoingEmail([], $mailable);
        $mailer->saveFromMailable();

        $this->info('Account created successfully.');
    }

    /**
     * Verifies if email provided is unique.
     *
     * @param $email
     *
     * @return bool
     */
    protected function isUnique($email): bool
    {
        $user = User::where('email', $email)->first();

        return $user ? false : true;
    }
}
