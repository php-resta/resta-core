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
    public static function roleMake($routeName)
    {
        return static::where('route_name',$routeName)->where('role_id',auth()->user()->role_id);
    }
}

