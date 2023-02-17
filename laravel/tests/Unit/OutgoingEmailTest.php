<?php

namespace Tests\Unit;

use App\PolicyUpdater;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OutgoingEmailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the basic structure of the OutgoingEmail model.
     *
     * @return void
     * @group business_operation
     */
    public function testBusinessUsers()
    {
        $updater1 = factory(PolicyUpdater::class)->create();

        $email1 = factory(\App\OutgoingEmail::class)->create([
            'id' => 1,
            'subject' => 'test',
            'body' => 'test',
            'status' => 'sent',
            'error' => '',
            'config_value' => 'testing',
            'sent_at' => Carbon::now(),
            'related_id' => $updater1->id,
            'related_type' => PolicyUpdater::class,
        ]);

        // the first assertions help to validate that the factory logic above is correct
        $this->assertDatabaseHas('outgoing_emails', ['id' => 1]);

        // test the 'related' polymorphic relationship
        $this->assertInstanceOf(PolicyUpdater::class, $email1->related);
    }
}
