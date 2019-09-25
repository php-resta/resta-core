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
    protected $capsule = ['a','b','test','test2','test3','password'];

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

    /**
     * @var string
     */
    protected $b;

    /**
     * @var string
     */
    protected $password;

    /**
     * @return string
     */
    protected function b()
    {
        return $this->b;
    }

    /**
     * @return string
     */
    protected function password()
    {
        return md5($this->password);
    }


}