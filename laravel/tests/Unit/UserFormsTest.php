<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;

use App\Business;
use App\Form;
use App\FormTemplate;
use App\FormTemplateRule;
use Bentericksen\Forms\FormsPopulator;

/**
 * Class UserTest - tests for the User model.
 */
class UserFormsTest extends TestCase
{

  private $business;

  private $templates;

  private $rules;

  protected function setUp(): void
  {
    parent::setUp();

    $this->business = factory(Business::class)->create([
      'primary_user_id' => 101,
      'primary_role' => 'owner',
      'secondary_1_first_name' => 'Alice',
      'secondary_1_last_name' => 'Smith',
      'secondary_1_email' => 'alice@example.com',
      'consultant_user_id' => 1,
      'additional_employees' => 10,
      'state' => 'OR',
      'type' => 'dental'
    ]);

    $this->rules = collect([
      factory(FormTemplateRule::class)->create([
        'name' => 'employee_numbers',
        'expression' => '(!form.min_employee and !form.max_employee) or (business.additional_employees >= form.min_employee and business.additional_employees <= form.max_employee)'
      ]),
      factory(FormTemplateRule::class)->create([
        'name' => 'state',
        'expression' => '(!form.state) or ("ALL" in form.state) or (business.state in form.state) or (business.state != "MT" && "Non-MT" in form.state)'
      ]),
      factory(FormTemplateRule::class)->create([
        'name' => 'industries',
        'expression' => '(!form.industries) or ("ALL" in form.industries) or (business.type in form.industries)'
      ]),
    ]);
  }

  /**
   * Test forms init.
   *
   * @return void
   */
  public function testFormEmployeeCountBusinessRules()
  {
    $templates = collect([
      factory(FormTemplate::class)->create([
        'business_id' => $this->business->id
      ]),
      factory(FormTemplate::class)->create([
        'business_id' => $this->business->id,
        'min_employee' => 5,
        'max_employee' => 20
      ]),
      factory(FormTemplate::class)->create([
        'business_id' => $this->business->id,
        'min_employee' => 50,
        'max_employee' => 100
      ])
    ]);

    $populator = new FormsPopulator($this->business, $templates, $this->rules);

    $this->assertEquals($populator->isFormTemplateApplicable($templates->get(0)), true);

    $this->assertEquals($populator->isFormTemplateApplicable($templates->get(1)), true);

    $this->assertEquals($populator->isFormTemplateApplicable($templates->get(2)), false);

    $forms = $populator->forms();

    $this->assertEquals($forms->count(), 2);
  }

  public function testFormStateBusinessRules()
  {
    $templates = collect([
      factory(FormTemplate::class)->create([
        'business_id' => $this->business->id,
        'state' => ["AL"],
      ]),
      factory(FormTemplate::class)->create([
        'business_id' => $this->business->id,
        'state' => ["ALL"],
      ]),
      factory(FormTemplate::class)->create([
        'business_id' => $this->business->id,
        'state' => ["Non-MT"],
      ]),
      factory(FormTemplate::class)->create([
        'business_id' => $this->business->id,
        'state' => ["OR", "FL", "AL"],
      ])
    ]);

    $populator = new FormsPopulator($this->business, $templates, $this->rules);

    $this->assertEquals($populator->isFormTemplateApplicable($templates->get(0)), false);

    $this->assertEquals($populator->isFormTemplateApplicable($templates->get(1)), true);

    $this->assertEquals($populator->isFormTemplateApplicable($templates->get(2)), true);

    $this->assertEquals($populator->isFormTemplateApplicable($templates->get(3)), true);

    $forms = $populator->forms();

    $this->assertEquals($forms->count(), 3);
  }

  public function testFormNonMtStateBusinessRules()
  {
    $business = factory(Business::class)->create([
      'primary_user_id' => 101,
      'primary_role' => 'owner',
      'secondary_1_first_name' => 'Alice',
      'secondary_1_last_name' => 'Smith',
      'secondary_1_email' => 'alice@example.com',
      'consultant_user_id' => 1,
      'additional_employees' => 10,
      'state' => 'MT',
      'type' => 'dental'
    ]);

    $templates = collect([
      factory(FormTemplate::class)->create([
        'business_id' => $business->id,
        'state' => ["Non-MT"],
      ]),
      factory(FormTemplate::class)->create([
        'business_id' => $business->id,
        'state' => ["ALL"],
      ]),
      factory(FormTemplate::class)->create([
        'business_id' => $business->id,
        'state' => ["MT"],
      ])
    ]);

    $populator = new FormsPopulator($business, $templates, $this->rules);

    $this->assertEquals($populator->isFormTemplateApplicable($templates->get(0)), false);

    $this->assertEquals($populator->isFormTemplateApplicable($templates->get(1)), true);

    $this->assertEquals($populator->isFormTemplateApplicable($templates->get(2)), true);

    $forms = $populator->forms();

    $this->assertEquals($forms->count(), 2);
  }

  public function testFormBusinessTypeRules()
  {
    $templates = collect([
      factory(FormTemplate::class)->create([
        'business_id' => $this->business->id,
      ]),
      factory(FormTemplate::class)->create([
        'business_id' => $this->business->id,
        'industries' => ["ALL"],
      ]),
      factory(FormTemplate::class)->create([
        'business_id' => $this->business->id,
        'industries' => ["medical"],
      ])
    ]);

    $populator = new FormsPopulator($this->business, $templates, $this->rules);

    $this->assertEquals($populator->isFormTemplateApplicable($templates->get(0)), true);

    $this->assertEquals($populator->isFormTemplateApplicable($templates->get(1)), true);

    $this->assertEquals($populator->isFormTemplateApplicable($templates->get(2)), false);

    $forms = $populator->forms();

    $this->assertEquals($forms->count(), 2);
  }
}