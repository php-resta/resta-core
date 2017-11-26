<?php

namespace Resta\Foundation;

use Resta\Utils;

class Kernel {

    /**
     * @var $singleton
     */
    public $singleton=false;

    /**
     * @var $kernel
     */
    public $kernel;

    /**
     * @var array
     */
    protected $bootstrappers=[

        \Boot\Encrypter::class,
        \Resta\Booting\GlobalAccessor::class,
        \Resta\Booting\Exception::class,
        \Resta\Booting\UrlParse::class,
        \Boot\Router::class,
        \Boot\Response::class,
    ];

    /**
     * @return mixed
     */
    public function kernel(){

        //get kernel object
        return $this->kernel;
    }

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

    /**
     * @method bind
     * @param $object null
     * @param $callback null
     * @return mixed
     */
    public function bind($object=null,$callback=null){

        //we check whether the boolean value of the singleton variable used
        //for booting does not reset every time the object variable to be assigned to the kernel variable is true
        $this->singleton();

        //If the bind method does not have parameters object and callback, the value is directly assigned to the kernel object.
        //Otherwise, when the bind object and callback are sent, the closure class inherits
        //the applicationProvider object and the makeBind method is called
        return ($object===null) ? $this->build() : $this->make($object,$callback);

    }

    /**
     * @method singleton
     */
    public function singleton(){

        if($this->singleton===false){

            //after first initializing, the singleton variable is set to true,
            //and subsequent incoming classes can inherit the loaded object.
            $this->singleton=true;
            $this->kernel=new \stdClass;
        }

        //kernel object taken over
        return $this->kernel;
    }

    /**
     * @method build
     * @return mixed
     */
    public function build(){

        //kernel object taken over
        return $this->kernel;
    }

    /**
     * @method make
     * @param $object
     * @param $callback
     * @return mixed
     */
    public function make($object,$callback){

        //if a pre loader class wants to have before kernel values,
        //it must return a callback to the bind method
        $concrete=call_user_func($callback);

        //the value corresponding to the bind value for the global object is assigned and
        //the makeBind method is called for the dependency method.
        $this->kernel->{$object}=Utils::makeBind($concrete,$this->applicationProviderBinding($this))
            ->handle();

        return $this->kernel;
    }

    /**
     * @param $make
     * @return array
     */
    public function applicationProviderBinding($make){

        return [
            'app'=>$make
        ];
    }
}