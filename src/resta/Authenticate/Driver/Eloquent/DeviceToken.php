<?php
namespace Resta\Authenticate\Driver\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class DeviceToken extends Eloquent
{
    /**
     * @var $table string
     */
    protected $table = 'device_tokens';

    /**
     * @var array 
     */
    protected $fillable = ['user_id','token','token_integer','device_agent','device_agent_integer','expire'];
}
