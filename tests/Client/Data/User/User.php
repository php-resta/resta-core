<?php

namespace Resta\Core\Tests\Client\Data\User;

use Resta\Core\Tests\Client\Data\Client;
use Resta\Core\Tests\Client\Data\ClientProvider;
use Resta\Core\Tests\Client\Data\ClientGenerator;

class User extends ClientProvider
{
    //request and request generator
    use Client,UserGenerator,ClientGenerator;

    /**
     * @var array
     */
    protected $capsule = ['a','b','password','cgenerator1','cgenerator2','cgenerator3'];

    /**
     * The values ​​expected by the server.
     * @var array
     */
    protected $expected = ['password'];

    /**
     * @var array
     */
    protected $requestExcept = [];

    /**
     * mandatory http method.
     * @var array
     */
    protected $http = [];
}