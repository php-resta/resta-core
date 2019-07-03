<?php

namespace Resta\Role\Resource\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    /**
     * @var string
     */
    protected $table = 'users';
}

