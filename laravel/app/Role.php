<?php

namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    const ADMIN = 'admin';
    const CLIENT = 'client';
    const MANAGER = 'manager';
    const OWNER = 'owner';
    const CONSULTANT = 'consultant';
    const EMPLOYEE = 'employee';
}
