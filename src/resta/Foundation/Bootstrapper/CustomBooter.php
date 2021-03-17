<?php

namespace Resta\Foundation\Bootstrapper;

use Resta\Support\Utils;
use Resta\Foundation\PathManager\StaticPathList;

class CustomBooter
{
    /**
     * @var null
     */
    protected $boot;

    /**
     * @var string
     */
    protected $bootNamespace;

    /**
     * @var array
     */
    protected $booterList = [];

    /**
     * @var string
     */
    protected $customBooter = 'originGroups';

    /**
     * CustomBooter constructor.
     * @param $boot
     */
    public function __construct($boot) {

        //Let's assign
        //this variable to the name of our boot list.
        $this->boot = $boot;
    }

    /**
     * @return mixed
     */
    public function getBooterList()
    {
        //kernel boot name
        $kernelBootName = $this->boot;

        //We specify the method call for the booter list.
        return app()->manifest($kernelBootName);
    }
}