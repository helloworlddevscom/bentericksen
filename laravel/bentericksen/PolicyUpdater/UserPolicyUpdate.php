<?php

namespace Bentericksen\PolicyUpdater;

use App\Business;
use App\Policy;
use App\PolicyTemplateUpdate;
use App\User;
use DB;
use Carbon\Carbon;

class UserPolicyUpdate extends PolicyUpdateAbstract
{
    protected $viewUser;

    private $user;

    private $users;

    private $userBusinessId;

    public function __construct($user)
    {
        $this->viewUser = $user;
        $this->user = User::find($user->id);

        if ($this->user->hasRole('admin')) {
            return;
        }

        $this->userBusinessId = $this->viewUser->business_id;
    }

    /**
     * Set policy updates
     * @return array
     */
    public function setUpdatedPolicies()
    {
        if ($this->user->hasRole('employee')) {
            $users = $this->user->business->users->pluck('id')->toArray();
        } else {
            $users = [$this->user->id];
        }

        
        // The user_policy_updates table can and does have some non-owner users (which're being included from the
        // business.secondary_{1,}_email fields. These may or may not be manager accounts. So, since
        // business.primary_user_id should be included in every policy update, it'd be valid for all cases.
        $this->updated_policies = DB::table('user_policy_updates')
            ->whereIn('user_id', $users)
            ->where('accepted_at', '0000-00-00 00:00:00')
            ->groupBy('policies')
            ->orderBy('policy_template_update_id')
            ->get();
        
        return $this->getUpdatedPolicies();
    }

    /**
     * Gets the associated business id
     * @return int
     */
    public function getBusinessId() {
        return $this->userBusinessId;
    }

    /**
     * Set Users
     */
    public function setUsers($users = []) {
        $this->users = $users;
    }

    /**
     * Accept policy updates
     * @return void
     */
    public function acceptUpdates($id) {

        if (empty($this->users)) {
            return;
        }

        return DB::table('user_policy_updates')
            ->where('policy_template_update_id', $id)
            ->whereIn('user_id', $this->users)
            ->update([
                'accepted_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
    }
}
