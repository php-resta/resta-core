<?php

namespace Resta\Routing;

use Resta\ApplicationProvider;
use Resta\Contracts\ApplicationContracts;
use Resta\Utils;

class KernelRouterProcess extends ApplicationProvider {

    /**
     * @var array $routerSpecification
     */
    protected $routerSpecification=[
        'pickRouter',
        'stackRouter'
    ];

    /**
     * @return mixed
     */
    public function router(){

        //We are developing some features for the benefit of the user through route privatization.
        //These are the customizations that are available in the routerSpecification object.
        //By running these individually, we assign reference values to the router variable,
        //which is the actual kernel object, so route customization takes place.
        return $this->routerProcess(function($router){
            return $router;
        });
    }

    /**
     * @param callable $callback
     */
    private function routerProcess(callable $callback){

        //After running the router specification sequence one by one, we collect reference values for the kernel router.
        array_walk($this->routerSpecification,[$this,'routerSpecification']);

        //Then we return the routerSpecifications object that is assigned for the kernel to the router method.
        return call_user_func_array($callback,[$this->singleton()->routerSpecifications['router']]);
    }

    /**
     * @param $specification
     * @param $key
     */
    private function routerSpecification($specification,$key){
        $this->{$specification}();
    }

    /**
     * @method pickRouter
     * @return mixed|void
     */
    private function pickRouter(){

        $singleton=$this->singleton();
        $singleton->routerSpecifications['router']=(isset($singleton->pick)) ? $singleton->pick[0] : $singleton->router;
    }

    /**
     * @method stackRouter
     * @return mixed|void
     */
    private function stackRouter(){

        $singleton=$this->singleton();
        $singleton->routerSpecifications['router']=(isset($singleton->stack)) ? $singleton->stack : $singleton->router;
    }

}