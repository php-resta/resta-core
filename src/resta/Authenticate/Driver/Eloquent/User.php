<?php
namespace Resta\Authenticate\Driver\Eloquent;

use Store\Packages\Database\Eloquent\Connection as Eloquent;

class User extends Eloquent
{
    /**
     * @var $table string
     */
    protected $table='users';
}
