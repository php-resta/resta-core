<?php

namespace Resta\Core\Tests\Client\Data\User4;

use Resta\Core\Tests\Client\Data\Client;
use Resta\Core\Tests\Client\Data\ClientProvider;
use Resta\Core\Tests\Client\Data\ClientGenerator;

class User4 extends ClientProvider
{
    //request and request generator
    use Client,User4Generator,ClientGenerator;

    /**
     * @var array
     */
    protected $capsule = [];

    /**
     * The values ​​expected by the server.
     * @var array
     */
    protected $expected = ['status'];

    /**
     * @var array
     */
    protected $requestExcept = [];

    /**
     * @var array
     */
    protected $groups = ['items'];

    /**
     * mandatory http method.
     * @var array
     */
    protected $http = [];

    /**
     * @var int
     */
    protected $status;

    /**
     *
     * @rule(integer)
     * @return int
     */
    protected function status()
    {
        return $this->status;
    }


}