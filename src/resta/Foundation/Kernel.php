<?php

namespace Resta\Foundation;

use Resta\Utils;

class Kernel {

    /**
     * @var $kernel
     */
    protected $kernel;

    /**
     * @var array
     */
    protected $bootstrappers=[

        \Boot\Encrypter::class,
        \Resta\Booting\SymfonyRequest::class,
        \Boot\Router::class
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
            Utils::makeBind($bootstrapper,[
                'app'=>$app
            ])
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

    /**
     * @method bind
     * @return \stdClass
     */
    public function bind(){

        return $this->kernel=new \stdClass;
    }
}