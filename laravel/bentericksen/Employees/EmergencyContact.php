<?php

namespace Bentericksen\Employees;

use \App\EmergencyContact as Contact;
use App\EmergencyContact as EmergencyContactModel;
use DB;

class EmergencyContact
{

    private $userId;

    private $contacts;

    public function __construct($userId = null)
    {
        if ($userId) {
            $this->userId = $userId;
            $this->contacts = Contact::where('user_id', $userId)->get();
        }

        return $this;
    }

    public function create($request, $key)
    {
        $contact = new EmergencyContactModel;

        $contact->user_id = $this->userId;
        $contact->name = $request['name'];
        $contact->phone1 = $request['phone1'];
        $contact->phone1_type = $request['phone1_type'];
        $contact->phone2 = $request['phone2'];
        $contact->phone2_type = $request['phone2_type'];
        $contact->phone3 = $request['phone3'];
        $contact->phone3_type = $request['phone3_type'];
        $contact->relationship = $request['relationship'];
        $contact->is_primary = $key == "primary" ? 1 : 0;

        $contact->save();
    }

    public function getContacts()
    {
        return $this->contacts->count() > 0 ? $this->contacts : null;
    }

    public function save($request)
    {
        if (!is_array($request) || empty($request)) {
            return;
        }

        foreach ($request as $key => $values) {
            // checking if $key is a contact ID (update) or "primary/secondary" (create)
            if (!is_numeric($key)) {
                $this->create($values, $key);
                continue;
            }

            DB::table('emergency_contact')->where('id', $key)->update($values);
        }
    }

}