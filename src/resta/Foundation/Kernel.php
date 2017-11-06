<?php

namespace Resta\Foundation;

use Resta\Utils;

class Kernel {

    protected $bootstrappers=[

        \Boot\Encrypter::class
    ];

    /**
     * @method bootstrappers
     * @param $app \Resta\Contracts\Application
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
}