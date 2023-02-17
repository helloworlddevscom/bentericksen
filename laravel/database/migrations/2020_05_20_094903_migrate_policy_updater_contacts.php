<?php

use App\PolicyUpdater;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigratePolicyUpdaterContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        PolicyUpdater::all()->each(function ($updater) {
            $emails = json_decode($updater->emails);
            $emails = empty($emails) ? [] : array_filter(explode(',', array_pop($emails)));
            $additionalEmails = json_decode($updater->additional_emails);
            $additionalEmails = empty($additionalEmails) ? [] : array_filter(explode(',', array_pop($additionalEmails)));

            $emails = array_map(function ($email) {
                return [
                    'email' => $email,
                ];
            }, $emails);

            $updater->contacts()->createMany($emails);

            if (empty($additionalEmails)) {
                return;
            }

            $additionalEmails = array_map(function ($email) {
                return [
                    'email' => $email,
                    'contact_type' => 'additional',
                ];
            }, $additionalEmails);

            $updater->contacts()->createMany($additionalEmails);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
