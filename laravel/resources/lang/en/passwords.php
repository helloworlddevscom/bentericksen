<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Password Reminder Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | has failed, such as for an invalid token or invalid new password.
    |
    */

    'password' => 'Passwords must be at least six characters and match the confirmation.',
    'user' => 'An email with the reset link was sent to the email account below.',
    'token' => 'Sorry, the password reset link has expired for security reasons. Please click <a href="/password/email">Forgot Password</a> again to send a new link.',
    // NOTE: we're hard-coding the 24 hours value since the 'expire' value is set in laravel/config/auth.php
    'sent' => 'An email with the reset link was sent to the email account below.<br/>NOTE: The reset link will expire in 24 hours.',
    'throttled' => 'Please wait before retrying.',
    'reset' => 'Your password has been reset!',

];
