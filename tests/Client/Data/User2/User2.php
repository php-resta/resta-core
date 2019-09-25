<?php

namespace Resta\Core\Tests\Client\Data\User2;

use Resta\Core\Tests\Client\Data\Client;
use Resta\Core\Tests\Client\Data\ClientProvider;
use Resta\Core\Tests\Client\Data\ClientGenerator;

class User2 extends ClientProvider
{
    //request and request generator
    use Client,User2Generator,ClientGenerator;

    /**
     * @var array
     */
    protected $capsule = ['a','b','password'];

    /**
     * The values ​​expected by the server.
     * @var array
     */
    protected $expected = ['password'];

    /**
     * @var array
     */
    protected $requestExcept = ['a'];

    /**
     * mandatory http method.
     * @var array
     */
    protected $http = [];
}