<?php

namespace Resta\Foundation;

class Kernel extends Container {

    /**
     * @var $boot
     */
    protected $boot=false;

    /**
     * @var array
     */
    protected $middlewareGroups=[

        'Resta\Booting\ApplicationInstanceLoader',
        'Resta\Booting\GlobalAccessor',
        'Resta\Booting\Exception',
        'Resta\Booting\UrlParse',
        'Resta\Booting\LogProvider',
        'Resta\Booting\Environment',
        'Resta\Booting\Encrypter',
        'Resta\Booting\ConfigLoader',
        'Resta\Booting\ServiceContainer',
        'Resta\Booting\Middleware'
    ];

    /**
     * @var array
     */
    protected $bootstrappers=[

        'Resta\Booting\Console',
        'Resta\Booting\RouteProvider',
        'Resta\Booting\ResponseManager',

    ];


    /**
     * @method bootstrappers
     * @param $app \Resta\Contracts\ApplicationContracts
     * @param $strappers bootstrappers
     */
    protected function bootstrappers($app,$strappers='bootstrappers'){

        //The boot method to be executed can be specified by the user.
        //We use this method to know how to customize it.
        BootFireCallback::setBootFire([$app,$strappers],function($boot){

            //kernel boots run and service container{
            //makeBuild for service Container
            pos($boot)->bootFire($boot);
        });

    }

    /**
     * @method devEagers
     * @param $app \Resta\Contracts\ApplicationContracts
     * @return void
     */
    protected function devEagers($app){

    }

    /**
     * @method middlewareLoaders
     * @param $app \Resta\Contracts\ApplicationContracts
     * @return void
     */
    protected function middlewareLoaders($app){

        //When your application is requested, the middleware classes are running before all bootstrapper executables.
        //Thus, if you make http request your application, you can verify with an intermediate middleware layer
        //and throw an exception.
        $this->bootstrappers($app,'middlewareGroups');
    }

    /**
     * @method devEagerConfiguration
     * @return void
     */
    public function devEagerConfiguration(){

        //kernel eager for dev
        $this->devEagers($this);
    }

    /**
     * @method booting
     * @return void
     */
    public function booting(){

        //check boot for only once
        //if boot is true,booting classes would not run
        if($this->boot){
            return;
        }

        //system booting for app
        //pre-loaders are the most necessary classes for the system.
        $this->bootstrappers($this);

        //boot true
        $this->boot=true;
    }

    /**
     * @method middleware
     * @return void
     */
    public function middleware(){

        //pre-loaders user-based
        $this->middlewareLoaders($this);
    }

}