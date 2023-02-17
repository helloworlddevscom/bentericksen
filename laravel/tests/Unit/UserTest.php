<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;

/**
 * Class UserTest - tests for the User model.
 */
class UserTest extends TestCase
{
    /**
     * Test the birthday calculations.
     *
     * @return void
     * @group business_operation
     */
    public function testUserBirthday()
    {
        $today = Carbon::today();
        $a_year_from_today = new Carbon('+1 year');

        $user = new \App\User();
        $this->assertNull($user->getNextBirthday()); // with null date

        $user->dob = '2015-01-01';
        $next_birthday = new Carbon($user->getNextBirthday());

        $this->assertTrue($next_birthday->greaterThanOrEqualTo($today));
        $this->assertTrue($next_birthday->lessThan($a_year_from_today));
        $this->assertEquals(1, $next_birthday->month);
        $this->assertEquals(1, $next_birthday->day);

        $user->dob = '2012-02-29';
        $next_birthday = new Carbon($user->getNextBirthday());

        $this->assertTrue($next_birthday->greaterThanOrEqualTo($today));
        $this->assertTrue($next_birthday->lessThan($a_year_from_today));
        if ($next_birthday->isLeapYear()) {
            $this->assertEquals(2, $next_birthday->month);
            $this->assertEquals(29, $next_birthday->day);
        } else {
            $this->assertEquals(3, $next_birthday->month);
            $this->assertEquals(1, $next_birthday->day);
        }
    }

    /**
     * Test the anniversary calculations.
     *
     * @return void
     * @group business_operation
     */
    public function testUserAnniversary()
    {
        $today = Carbon::today();
        $a_year_from_today = new Carbon('+1 year');

        $user = new \App\User();
        $this->assertNull($user->getNextAnniversary());

        $user->hired = '2015-01-01';
        $next_anniversary = new Carbon($user->getNextAnniversary());

        $this->assertTrue($next_anniversary->greaterThanOrEqualTo($today));
        $this->assertTrue($next_anniversary->lessThan($a_year_from_today));
        $this->assertEquals(1, $next_anniversary->month);
        $this->assertEquals(1, $next_anniversary->day);

        $user->hired = '2012-02-29';
        $next_anniversary = new Carbon($user->getNextAnniversary());

        $this->assertTrue($next_anniversary->greaterThanOrEqualTo($today));
        $this->assertTrue($next_anniversary->lessThan($a_year_from_today));
        if ($next_anniversary->isLeapYear()) {
            $this->assertEquals(2, $next_anniversary->month);
            $this->assertEquals(29, $next_anniversary->day);
        } else {
            $this->assertEquals(3, $next_anniversary->month);
            $this->assertEquals(1, $next_anniversary->day);
        }
    }
}
