<?php
namespace Resta\Authenticate\Driver\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    protected $hidden = ['id','password','token'];

    /**
     * User constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = app()->get('authenticateTable');
    }
}
