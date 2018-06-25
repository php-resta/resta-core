<?php

namespace Resta\Foundation;

use Resta\Container\Container;
use Resta\Contracts\KernelContracts;

class Kernel extends Container implements KernelContracts {

    /**
     * @var $boot
     */
    protected $boot=false;

    /**
     * @var array
     */
    protected $devEagerGroups=[];

    /**
     * @var array
     */
    protected $originGroups=[

        'Resta\Booting\GlobalAccessor',
        'Resta\Booting\Exception',
        'Resta\Booting\UrlParse',
        'Resta\Booting\LogProvider',
        'Resta\Booting\Environment',
        'Resta\Booting\Encrypter',
        'Resta\Booting\ConfigLoader',
        'Resta\Booting\ServiceContainer',
        'Resta\Booting\EventDispatcher',
        'Resta\Booting\Console',
    ];

    protected $middlewareGroups=[

        'Resta\Booting\Middleware'
    ];

    /**
     * @var array
     */
    protected $reflectionGroups=[

        'Resta\Booting\RouteProvider',
        'Resta\Booting\ResponseManager',

    ];


    /**
     * @method bootstrappers
     * @param $app \Resta\Contracts\ApplicationContracts
     * @param $strappers bootstrappers
     */
    protected function bootstrappers($app,$strappers){

        //The boot method to be executed can be specified by the user.
        //We use this method to know how to customize it.
        BootFireCallback::setBootFire([$app,$strappers],function($boot){

            //kernel boots run and service container{
            //makeBuild for service Container
            pos($boot)->bootFire($boot);
        });

    }

    /**
     * @param $group
     * @param $booting
     */
    public function callBootstrapperProcess($group,$booting,$onion=true){

        if($onion){

            // we will implement a special onion method here and
            // pass our bootstraper classes through this method.
            // Our goal here is to implement the middleware layer correctly.
            $this->makeBind(BootstrapperPeelOnion::class)->onionBoot([$group,$booting],function() use($group){
                $this->bootstrappers($this,$group);
            });

            return false;
        }

        //system booting for app
        //pre-loaders are the most necessary classes for the system.
        $this->bootstrappers($this,$group);

    }

}