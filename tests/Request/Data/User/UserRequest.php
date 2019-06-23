<?php

namespace Resta\Core\Tests\Request\Data\User;

use Resta\Core\Tests\Request\Data\Request;
use Resta\Core\Tests\Request\Data\RequestProvider;
use Resta\Core\Tests\Request\Data\RequestGenerator;

class UserRequest extends RequestProvider
{
    //request and request generator
    use Request,UserRequestGenerator,RequestGenerator;

    /**
     * The values ​​expected by the server.
     * @var array
     */
    protected $expected = ['username|email'];

    /**
     * mandatory http method.
     * @var array
     */
    protected $http = [];

    /**
     * @var string
     */
    protected $username;

    /**
     * get username input value for request
     *
     * @rule(min6Char)
     * @return string
     */
    protected function username()
    {
        return $this->username;
    }


}