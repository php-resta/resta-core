<?php
namespace Resta\Authenticate\Driver\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class DeviceToken extends Eloquent
{
    /**
     * @var array
     */
    protected $fillable = ['user_id','token','token_integer','device_agent','device_agent_integer','expire'];

    /**
     * User constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = app()->get('authenticateDeviceTokenTable');
    }
}
