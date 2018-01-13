<?php

namespace Resta\Foundation;

use Resta\Booting\ConfigLoader;
use Resta\Utils;

class Kernel extends Container {

    /**
     * @var array
     */
    protected $middlewareGroups=[

        \Resta\Booting\Exception::class,
        \Resta\Booting\GlobalsForApplicationAndConsole::class,
        \Resta\Booting\GlobalAccessor::class,
        \Resta\Booting\UrlParse::class,
        \Resta\Booting\Environment::class,
        \Boot\Encrypter::class,
        \Resta\Booting\ConfigLoader::class,
        \Resta\Booting\ServiceContainer::class,
        \Resta\Booting\Middleware::class
    ];

    /**
     * @var array
     */
    protected $bootstrappers=[

        \Boot\Router::class,
        \Boot\Response::class,

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