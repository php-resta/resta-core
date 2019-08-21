<?php

namespace Resta\Role\Resource\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Role extends Eloquent
{
    /**
     * @var string
     */
    protected $table = 'roles';

    /**
     * @param $role
     */
    public static function roleMake($role)
    {
        $roleCriteria = explode(':',$role);
        
        if(isset($roleCriteria[1])){
            $role = static::where('name',$roleCriteria[0])->
            where('status',1)->where('is_deleted',0)->where('role_state',$roleCriteria[1])->first();
            
            if(is_null($role)){
                exception('roleDefinitorException')->runtime('roleDefinitorException');
            }
            
            if(auth()->user()->role_id == $role->id){
                return true;
            }
            
            return false;
        }
        
    }
}

