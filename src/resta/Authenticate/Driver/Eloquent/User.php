<?php
namespace Resta\Authenticate\Driver\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    /**
     * @var $table string
     */
    protected $table='users';
}
