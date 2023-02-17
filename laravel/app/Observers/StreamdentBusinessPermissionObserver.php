<?php

namespace App\Observers;

use App\BusinessPermission;
use App\Jobs\PerformStreamdentBusinessPermissionProcess;
use Illuminate\Support\Facades\Log;

class StreamdentBusinessPermissionObserver
{
	public function created(BusinessPermission $permission) {
		return;
	}

	public function updated(BusinessPermission $permission) {
    if (!in_array('m300', array_keys($permission->getDirty()))) {
      return;
    }
    
    if (!$permission->business->sop_active) {
      return;
    }
    
    PerformStreamdentBusinessPermissionProcess::dispatch($permission);
	}

	public function deleted(BusinessPermission $permission) {
		return;
	}

}
