<?php

namespace Resta\Core\Tests\Client\Data\User5;

use Resta\Core\Tests\Client\Data\Client;
use Resta\Core\Tests\Client\Data\ClientProvider;
use Resta\Core\Tests\Client\Data\ClientGenerator;

class User5 extends ClientProvider
{
    //request and request generator
    use Client,User5Generator,ClientGenerator;

    /**
     * @var array
     */
    protected $capsule = [];

    /**
     * The values ​​expected by the server.
     * @var array
     */
    protected $expected = [];

    /**
     * @var array
     */
    protected $requestExcept = ['cgenerator1','cgenerator2','cgenerator3'];

    /**
     * @var array
     */
    protected $groups = [];

    /**
     * mandatory http method.
     * @var array
     */
    protected $http = [];

    /**
     * @var int
     */
    protected $code1;

    /**
     *
     * @rule(integer:min6Char)
     * @return int
     */
    protected function code1()
    {
        return $this->code1;
    }
}