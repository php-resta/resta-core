<?php

namespace Resta\Router;

use Resta\Foundation\ApplicationProvider;

class KernelRouterProcess extends ApplicationProvider
{
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
    private function pickRouter()
    {
        $singleton=core();
        $singleton->routerSpecifications['router']=(isset($singleton->pick)) ? $singleton->pick[0] : $singleton->router;
    }

    /**
     * @return mixed
     */
    public function router()
    {
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
     * @return mixed
     */
    private function routerProcess(callable $callback)
    {
        //After running the router specification sequence one by one, we collect reference values for the kernel router.
        array_walk($this->routerSpecification,[$this,'routerSpecification']);

        //Then we return the routerSpecifications object that is assigned for the kernel to the router method.
        return call_user_func_array($callback,[core()->routerSpecifications['router']]);
    }

    /**
     * @param $specification
     * @param $key
     */
    private function routerSpecification($specification,$key)
    {
        $this->{$specification}();
    }

    /**
     * @method stackRouter
     * @return mixed|void
     */
    private function stackRouter()
    {
        //singleton object
        $singleton=core();

        //if there is no singleton pick
        //If this is the case, we collect these values and assign them to the router variable.
        //if it is not, the router will send the default value to the output.
        if(!isset($singleton->pick)){
            $singleton->routerSpecifications['router']=(isset($singleton->stack)) ? $singleton->stack : $singleton->router;
        }
    }

}