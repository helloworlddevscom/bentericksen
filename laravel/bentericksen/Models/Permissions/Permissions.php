<?php namespace Bentericksen\Models\Permissions;

use App\BusinessPermission;
use Bentericksen\ViewAs\ViewAs;

/**
 * Class Permissions
 *
 * A helper class for the Permissons editing page.
 *
 * @package Bentericksen\Models\Permissions
 * @see \App\Http\Controllers\User\PermissionController
 * @see \App\BusinessPermission
 */
class Permissions {
	
	public $permissions;
	
	public function __construct()
	{
        $viewAs = new ViewAs();
        $user = $viewAs->getUser();
        if ($user) {
            $this->permissions = BusinessPermission::where('business_id', $user->business_id)->first();
            if ($this->permissions == null) {
                // business doesn't have a permissions row, so we create one
                $this->permissions = $this->createNewPermissions($user);
            }
        } else {
            $this->permissions = null;
        }
    }
	
	public function get($key)
	{
		if(isset($this->permissions->$key))
		{
			return $this->permissions->$key;
		}
	}

    /**
     * Creates a new BusinessPermissions object for the user's business.
     *
     * @param $user
     * @return BusinessPermission
     */
	private function createNewPermissions($user)
	{
		$permission = new BusinessPermission;
		$permission->business_id = $user->business_id;
		$permission->save();
		
		$permissions = BusinessPermission::findOrFail($permission->id);		
		return $permissions;
	}
}
