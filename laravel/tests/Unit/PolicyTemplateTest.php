<?php

namespace Tests\Unit;

use App\Business;
use App\PolicyTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class PolicyTemplateTest.
 *
 * Tests for the PolicyTemplate model class.
 *
 * @see \App\PolicyTemplate
 */
class PolicyTemplateTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Test the required/optional status of the policy.
     *
     * @return void
     * @group policy
     */
    public function testRequirements()
    {
        // examples of existing 'requirement' fields in the db:
        // ["required"]  // required for all
        // ["optional"]  // optional for all
        // ["doptional","moptional"]  // optional for dental and medical (effectively optional for all)
        // ["drequired","coptional","voptional","mrequired"]  // required for dental/medical, optional for commercial and veterinary

        $template = new PolicyTemplate();

        $business = new Business();

        // required for all
        $template->requirement = ['required'];
        $business->type = 'dental';
        $this->assertEquals('required', $template->getRequirement($business));
        $business->type = 'veterinary';
        $this->assertEquals('required', $template->getRequirement($business));
        $business->type = 'something else';
        $this->assertEquals('required', $template->getRequirement($business));

        // optional for all
        $template->requirement = ['optional'];
        $business->type = 'dental';
        $this->assertEquals('optional', $template->getRequirement($business));
        $business->type = 'veterinary';
        $this->assertEquals('optional', $template->getRequirement($business));
        $business->type = 'something else';
        $this->assertEquals('optional', $template->getRequirement($business));

        // required for one type, optional for others
        $template->requirement = ['drequired'];
        $business->type = 'dental';
        $this->assertEquals('required', $template->getRequirement($business));
        $business->type = 'veterinary';
        $this->assertEquals('optional', $template->getRequirement($business));
        $business->type = 'something else';
        $this->assertEquals('optional', $template->getRequirement($business));

        // optional for a few types (this is effectively the same as optional for all)
        $template->requirement = ['doptional', 'moptional'];
        $business->type = 'dental';
        $this->assertEquals('optional', $template->getRequirement($business));
        $business->type = 'medical';
        $this->assertEquals('optional', $template->getRequirement($business));
        $business->type = 'veterinary';
        $this->assertEquals('optional', $template->getRequirement($business));
    }
}
