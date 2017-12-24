<?php

namespace Resta\Foundation;

use Resta\Utils;

class Kernel extends Container {

    /**
     * @var array
     */
    protected $bootstrappers=[

        \Resta\Booting\Exception::class,
        \Resta\Booting\GlobalsForApplicationAndConsole::class,
        \Resta\Booting\GlobalAccessor::class,
        \Resta\Booting\UrlParse::class,
        \Resta\Booting\Environment::class,
        \Boot\Encrypter::class,
        \Resta\Booting\ServiceContainer::class,
        \Boot\Router::class,
        \Boot\Response::class,

    ];


    /**
     * @method bootstrappers
     * @param $app \Resta\Contracts\ApplicationContracts
     */
    protected function bootstrappers($app){

        //kernel boots run and service container
        //makeBuild for service Container
        foreach ($this->bootstrappers as $bootstrapper){

            //set makeBuild for kernel boots
            Utils::makeBind($bootstrapper,$this->applicationProviderBinding($app))
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

    }


}