<?php

namespace Resta\Role\Resource\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Permission extends Eloquent
{
    /**
     * @var string
     */
    protected $table = 'permissions';

    /**
     * @param $routeName
     */
    public static function permissionMake($routeName)
    {
        $permission = static::where('route_name',$routeName)->where('role_id',auth()->user()->role_id);
        
        return ($permission->count()) ? true : false;
    }
}

