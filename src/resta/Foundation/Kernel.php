<?php

namespace Resta\Foundation;

use Boot\Encrypter;
use Boot\Logger;
use Resta\Booting\ConfigLoader;
use Resta\Booting\Environment;
use Resta\Booting\Exception;
use Resta\Booting\GlobalAccessor;
use Resta\Booting\GlobalsForApplicationAndConsole;
use Resta\Booting\LogProvider;
use Resta\Booting\Middleware;
use Resta\Booting\ResponseManager;
use Resta\Booting\RouteProvider;
use Resta\Booting\ServiceContainer;
use Resta\Booting\UrlParse;
use Resta\Utils;

class Kernel extends Container {

    /**
     * @var array
     */
    protected $middlewareGroups=[

        GlobalAccessor::class,
        Exception::class,
        GlobalsForApplicationAndConsole::class,
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

        RouteProvider::class,
        ResponseManager::class,

    ];


    /**
     * @method bootstrappers
     * @param $app \Resta\Contracts\ApplicationContracts
     * @param $strappers bootstrappers
     */
    protected function bootstrappers($app,$strappers='bootstrappers'){

        //kernel boots run and service container
        //makeBuild for service Container
        foreach ($this->{$strappers} as $strapper){

            //set makeBuild for kernel boots
            Utils::makeBind($strapper,$this->applicationProviderBinding($app))
                ->boot();
        }
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


}