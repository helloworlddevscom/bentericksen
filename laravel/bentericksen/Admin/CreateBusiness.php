<?php

namespace Bentericksen\Admin;

use App\Business;
use App\Form;
use App\FormTemplate;
use App\Http\Requests\Request;
use App\Role;
use Bentericksen\Permissions\NewBusinessPermissionsSetup;
use DB;

class CreateBusiness
{
    /**
     * @var array
     */
    protected $businessValues;

    /**
     * @var Business
     */
    private $business;

    /**
     * Populates the properties of the Business object from the data in the Request object.
     *
     * @param Request $request
     * @param \App\User $primaryUser
     */
    public function load($request, $primaryUser)
    {
        $primary_role = $primaryUser->roles()
            ->where('id', $request->input('primary_role'))
            ->first();
        $secondary_1_role = $request->input("secondary_1_role") ?
            Role::find($request->input("secondary_1_role"))->name : null;
        $secondary_2_role = $request->input("secondary_2_role") ?
            Role::find($request->input("secondary_2_role"))->name : null;

        $this->businessValues = [
            "name" => $request->input("name"),
            "address1" => $request->input("address1"),
            "address2" => $request->input("address2"),
            "city" => $request->input("city"),
            "state" => $request->input("state"),
            "postal_code" => $request->input("postal_code"),
            "phone1" => $request->input("phone1"),
            "phone1_type" => $request->input("phone1_type"),
            "phone2" => $request->input("phone2"),
            "phone2_type" => $request->input("phone2_type"),
            "phone3" => $request->input("phone3"),
            "phone3_type" => $request->input("phone3_type"),
            "fax" => $request->input("fax"),
            "website" => $request->input("website"),
            "primary_user_id" => $primaryUser->id,
            "primary_role" => $primary_role->name,
            "secondary_1_first_name" => $request->input("secondary_1_first_name"),
            "secondary_1_middle_name" => $request->input("secondary_1_middle_name"),
            "secondary_1_last_name" => $request->input("secondary_1_last_name"),
            "secondary_1_prefix" => $request->input("secondary_1_prefix"),
            "secondary_1_suffix" => $request->input("secondary_1_suffix"),
            "secondary_1_email" => $request->input("secondary_1_email"),
            "secondary_1_role" => $secondary_1_role,
            "secondary_2_first_name" => $request->input("secondary_2_first_name"),
            "secondary_2_middle_name" => $request->input("secondary_2_middle_name"),
            "secondary_2_last_name" => $request->input("secondary_2_last_name"),
            "secondary_2_prefix" => $request->input("secondary_2_prefix"),
            "secondary_2_suffix" => $request->input("secondary_2_suffix"),
            "secondary_2_email" => $request->input("secondary_2_email"),
            "secondary_2_role" => $secondary_2_role,
            "type" => $request->input("type"),
            "subtype" => $request->input("subtype"),
            "consultant_user_id" => $request->input("consultant_user_id") == "" ? null : $request->input("consultant_user_id"),
            "referral" => $request->input("referral_affiliation", 0),
            "status" => $request->input("status"),
            "do_not_contact" => $request->input("dnc", 0),
            "asa_id" => 0,
            "enable_sop" => $request->input("enable_sop"),
            "hrdirector_enabled" => $request->input("hrdirector_enabled"),
            "bonuspro_enabled" => $request->input("bonuspro_enabled"),
            "bonuspro_expiration_date" => !empty($request->input("bonuspro_expiration_date")) ? $request->input("bonuspro_expiration_date") : null,
            "bonuspro_lifetime_access" => $request->input("bonuspro_lifetime_access", 0),
            // Set the employee_count_reminder to true for new businesses (suppresses reminder).  Upcoming ticket BEM-747
            // will create job to display reminder 75 days before ASA expiration.
            "employee_count_reminder" => true
        ];
    }


    /**
     * Saves new Business instance. Returns business object.
     *
     * @return \App\Business|static
     */
    public function save()
    {
        $this->business = Business::create($this->businessValues);

        // create the default set of employee classifications
        $classifications = [
            [
                'business_id' => $this->business->id,
                'name' => "Full-Time",
                'is_base' => 1,
                'is_enabled' => 1,
                'minimum_hours' => 8,
                'minimum_hours_interval' => 'day',
                'maximum_hours' => 8,
                'maximum_hours_interval' => 'day'
            ],
            [
                'business_id' => $this->business->id,
                'name' => "Part-Time",
                'is_base' => 0,
                'is_enabled' => 1,
                'minimum_hours' => 4,
                'minimum_hours_interval' => 'day',
                'maximum_hours' => 6,
                'maximum_hours_interval' => 'day'
            ],
            [
                'business_id' => $this->business->id,
                'name' => "Part-Time 1",
                'is_base' => 0,
                'is_enabled' => 1,
                'minimum_hours' => 4,
                'minimum_hours_interval' => 'day',
                'maximum_hours' => 6,
                'maximum_hours_interval' => 'day'
            ],
            [
                'business_id' => $this->business->id,
                'name' => "Part-Time 2",
                'is_base' => 0,
                'is_enabled' => 1,
                'minimum_hours' => 4,
                'minimum_hours_interval' => 'day',
                'maximum_hours' => 6,
                'maximum_hours_interval' => 'day'
            ],
            [
                'business_id' => $this->business->id,
                'name' => "Per-Diem",
                'is_base' => 0,
                'is_enabled' => 1,
                'minimum_hours' => 4,
                'minimum_hours_interval' => 'day',
                'maximum_hours' => 6,
                'maximum_hours_interval' => 'day'
            ],
            [
                'business_id' => $this->business->id,
                'name' => "Temporary",
                'is_base' => 0,
                'is_enabled' => 1,
                'minimum_hours' => 4,
                'minimum_hours_interval' => 'day',
                'maximum_hours' => 6,
                'maximum_hours_interval' => 'day'
            ],
            [
                'business_id' => $this->business->id,
                'name' => "Commission",
                'is_base' => 0,
                'is_enabled' => 1,
                'minimum_hours' => 4,
                'minimum_hours_interval' => 'day',
                'maximum_hours' => 6,
                'maximum_hours_interval' => 'day'
            ],
        ];

        foreach ($classifications as $classification) {
            DB::table('classifications')->insert($classification);
        }

        // create the default set of forms
        $this->copyForms();

        /**
         * Initializing default permissions for business user types.
         */
        $this->initPermissions();

        return $this->business;
    }

    /**
     * Helper method to create a set of Forms from the Form Templates
     */
    private function copyForms()
    {
        $templates = FormTemplate::all();
        foreach ($templates as $template) {
            $states = $template->state;

            $data = [
                'template_id' => $template->id,
                'business_id' => $this->business->id,
                'description' => $template->description,
                'status' => $template->status,
                'edited' => "no",
                'folder' => $template->getUploadPath() . DIRECTORY_SEPARATOR,
                'file' => $template->file_name,
            ];

            if ($states === null || in_array($this->business->state, $states) || in_array('ALL', $states) || (in_array('Non-MT', $states) && $this->business->state !== "MT")) {
                Form::create($data);
            }

        }
    }

    /**
     * Initialize permissions for Business' user types.
     *
     * @return $this
     */
    private function initPermissions()
    {
        $permissions = new NewBusinessPermissionsSetup($this->business);
        $permissions->init();

        return $this;
    }

}
