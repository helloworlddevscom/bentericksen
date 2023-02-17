<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => Str::random(10),
        'remember_token' => Str::random(10),
    ];
});

$factory->define(App\Business::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'address1' => $faker->address,
        'primary_user_id' => null,
        'consultant_user_id' => null,
        'secondary_1_first_name' => null,
        'secondary_1_last_name' => null,
        'secondary_1_email' => null,
        'secondary_2_first_name' => null,
        'secondary_2_last_name' => null,
        'secondary_2_email' => null,
        'status' => 'active',
        'additional_employees' => 20,  // used to calculate if template applies to biz
        'do_not_contact' => 0,
        'finalized' => 0,
        'ongoing_consultant_cc' => 0,
    ];
});

// these are the service agreements for the businesses
$factory->define(App\BusinessAsas::class, function (Faker $faker) {
    return [
        'business_id' => 1,  // Note: you should override this in create()
        'type' => 'annual-1-14',
        'expiration' => date('Y-m-d', time() + 86400 * 180),
        'status' => 'active',
    ];
});

// these are the service agreements for the businesses
$factory->define(App\BusinessPermission::class, function (Faker $faker) {
    return [
        'business_id' => 1,  // Note: you should override this in create()
        'm121' => 1,
        // TODO: add more permissions here when needed by future tests
    ];
});

$factory->define(App\PolicyTemplate::class, function (Faker $faker) {
    return [
        'content' => $faker->words(20, true),
        'category_id' => 1,
        'manual_name' => $faker->words(2, true),
        'effective_date' => date('Y-m-d', time() - 86400),
        // employee count, requirements, and state are
        // used to calculate if template applies to biz
        // (override these within your test if you need to test rules logic)
        'min_employee' => 0,
        'max_employee' => 50,
        // Note: the following must be arrays because they are JSON-encoded
        // when setting the attribute. @see \App\PolicyTemplate::setRequirementAttribute
        'requirement' => ['required'],  // must be array
        'state' => '["ALL"]',
        'status' => 'enabled',
    ];
});

$factory->define(App\PolicyTemplateUpdate::class, function (Faker $faker) {
    return [
        'content' => $faker->words(20, true),
        'category_id' => 1,
        'template_id' => null,
        'manual_name' => $faker->words(2, true),
        'effective_date' => date('Y-m-d', time() + 86400),
        'min_employee' => 0,
        'max_employee' => 50,
        'state' => '["ALL"]',
        'status' => 'enabled',
    ];
});

$factory->define(App\PolicyUpdater::class, function (Faker $faker) {
    return [
        'inactive_clients' => null,
        'policies' => '',
        'status' => 'pending',
        'additional_emails' => '',
    ];
});

$factory->define(App\Policy::class, function (Faker $faker) {
    return [
        'business_id' => null,
        'category_id' => 1,
        'template_id' => null,
        'manual_name' => $faker->words(2, true),
    ];
});

$factory->define(App\OutgoingEmail::class, function (Faker $faker) {
    return [
        'user_id' => null,
        'to_address' => 'test@example.com',
        'related_id' => null,
        'related_type' => null,
    ];
});

$factory->define(App\FormTemplate::class, function (Faker $faker) {
  return [
    'business_id' => 1,
    'name' => $faker->words(3, true),
    'category_id' => 999,
    'type' => 'regular',
    'file_name' => 'unit_test_form.pdf',
    'description' => $faker->words(10, true),
    'is_default' => 1,
    'status' => 'enabled',
    'state' => ["ALL"],
    'min_employee' => 0,
    'max_employee' => 999999,
    'industries' => ["ALL"]
  ];
});

$factory->define(App\FormTemplateRule::class, function (Faker $faker) {
  return [
    'name' => 'test::rule:01',
    'expression' => 'true == true'
  ];
});
