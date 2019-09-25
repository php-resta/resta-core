<?php

namespace Resta\Core\Tests\Client\Data\User3;

use Resta\Core\Tests\Client\Data\Client;
use Resta\Core\Tests\Client\Data\ClientProvider;
use Resta\Core\Tests\Client\Data\ClientGenerator;

class User3 extends ClientProvider
{
    //request and request generator
    use Client,User3Generator,ClientGenerator;

    /**
     * @var array
     */
    protected $capsule = [];

    /**
     * The values ​​expected by the server.
     * @var array
     */
    protected $expected = ['username|email'];

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