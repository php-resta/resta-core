<?php

namespace Resta\Foundation;

use Boot\Encrypter;
use Boot\Logger;
use Resta\Console\ConsoleBindings;
use Resta\Booting\ApplicationInstanceLoader;
use Resta\Booting\ConfigLoader;
use Resta\Booting\Environment;
use Resta\Booting\Exception;
use Resta\Booting\GlobalAccessor;
use Resta\Booting\Console;
use Resta\Booting\LogProvider;
use Resta\Booting\Middleware;
use Resta\Booting\ResponseManager;
use Resta\Booting\RouteProvider;
use Resta\Booting\ServiceContainer;
use Resta\Booting\UrlParse;
use Resta\Utils;


class Kernel extends Container {

    /**
     * @var $boot
     */
    protected $boot=false;

    /**
     * @var array
     */
    protected $middlewareGroups=[

        ApplicationInstanceLoader::class,
        GlobalAccessor::class,
        Exception::class,
        UrlParse::class,
        LogProvider::class,
        Environment::class,
        Encrypter::class,
        ConfigLoader::class,
        ServiceContainer::class,
        Middleware::class
    ];

    /**
     * @var array
     */
    protected $bootstrappers=[

        Console::class,
        RouteProvider::class,
        ResponseManager::class,

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