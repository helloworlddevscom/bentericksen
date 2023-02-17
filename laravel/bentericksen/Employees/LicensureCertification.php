<?php

namespace Bentericksen\Employees;

use App\User as UserModel;
use Carbon\Carbon;
use DB;
use Bentericksen\History\History;

class LicensureCertification
{

    /**
     * @var mixed|string
     */
    private $userId;

    /**
     * @var array
     */
    private $licensures = [];

    /**
     * @var \App\Business
     */
    private $business;

    /**
     * @var User
     */
    private $user;

    public function __construct($userId)
    {
        $this->userId = $userId;
        $this->user = UserModel::find($userId);
        $this->business = $this->user->business;

        if ($this->business) {
            $this->licensures = DB::table('licensure_certifications')
                ->whereIn('business_id', [0, $this->user->business_id])
                ->where('type', $this->business->type)
                ->orWhereNull('type')
                ->where('status', 'active')
                ->get();
        }

        $this->history = new History;
    }

    /**
     * Returns All Certifications
     *
     * @return mixed
     */
    public function getAll()
    {
        $licensures = DB::table('user_licensure_certifications')
            ->where('user_id', $this->userId)
            ->get();

        foreach ($licensures as $key => $licensure) {
            foreach ($this->licensures as $k => $v) {
                if ($licensure->licensure_certifications_id == $v->id) {
                    $licensures[$key]->name = $v->name;
                }
            }
        }

        return $licensures;
    }


    /**
     * Save License/Certification
     * @param $data
     */
    public function save($data)
    {
        if (isset($data['update'])) {
            foreach ($data['update'] as $update_key => $update) {
                $update_format = \Carbon\Carbon::createFromFormat('m/d/Y', $update)
                    ->toDateTimeString();

                DB::table('user_licensure_certifications')
                    ->where('id', $update_key)
                    ->update(['expiration' => $update_format]);
            }
        }

        if (isset($data['remove'])) {
            foreach ($data['remove'] as $remove) {
                $licensureID = DB::table('user_licensure_certifications')
                    ->where('id', $remove)
                    ->first()->licensure_certifications_id;

                $licensure = DB::table('licensure_certifications')
                    ->where('id', $licensureID)
                    ->first();

                $this->history->add([
                    'user_id' => $this->user->id,
                    'business_id' => $this->user->business_id,
                    'type' => 'status',
                    'created_at' => (new \Carbon\Carbon)->format('Y-m-d h:i:s'),
                    'note' => 'Licensure / Certification: ' . $licensure->name . ' removed',
                    'status' => 'active',
                ]);

                DB::table('user_licensure_certifications')
                    ->where('id', $remove)
                    ->delete();
            }
        }

        unset($data['remove']);

        if (isset($data['new'])) {
            foreach ($data['new'] as $new) {
                if (!isset($new['licensure_certifications_id']) ||
                    is_null($new['licensure_certifications_id']) ||
                    $new['licensure_certifications_id'] == "") {
                    continue;
                }

                $new['user_id'] = $this->userId;
                $new['created_at'] = (new \Carbon\Carbon)->format('Y-m-d h:i:s');

                if ($new['expiration'] !== "") {
                    $expiration = \Carbon\Carbon::createFromFormat('m/d/Y', $new['expiration']);
                    $new['expiration'] = $expiration->format('Y-m-d h:i:s');
                } else {
                    $expiration = null;
                }

                if ($new['licensure_certifications_id'] !== 'new') {
                    $licensure = DB::table('licensure_certifications')
                        ->where('id', $new['licensure_certifications_id'])
                        ->get()[0];
                    $name = $licensure->name;
                } else {
                    if (empty($new['name'])) {
                        continue;
                    }

                    DB::table('licensure_certifications')->insert([
                        'name' => $new['name'],
                        'business_id' => $this->business->id,
                        'status' => 'active',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    $new['licensure_certifications_id'] = DB::getPdo()->lastInsertId();
                    $name = $new['name'];
                    unset($new['name']);
                }

                DB::table('user_licensure_certifications')
                    ->insert($new);

                $this->history->add([
                    'user_id' => $this->user->id,
                    'business_id' => $this->user->business_id,
                    'type' => 'status',
                    'created_at' => (new \Carbon\Carbon)->format('Y-m-d h:i:s'),
                    'note' => 'Licensure / Certification: ' .
                        $name . ' added.  Expires: ' . $new['expiration'],
                    'status' => 'active',
                ]);
            }
        }

        $this->history->save();

        unset($data['new']);
    }
}
