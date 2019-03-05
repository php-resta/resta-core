<?php

namespace Resta\Middleware;

use Resta\Support\Utils;
use Resta\Foundation\ApplicationProvider;

class ApplicationMiddleware extends ApplicationProvider
{
    /**
     * @var array $middleware
     */
    protected $middleware=array();

    /**
     * @var bool
     */
    public $after=false;

    /**
     * @param MiddlewareKernelAssigner $middleware
     */
    public function handle(MiddlewareKernelAssigner $middleware)
    {
        //set define for middleware
        define('middleware',true);

        // If the after variable is sent normally,
        // the handle method will be executed.
        // If true, then the middleware will be executed.
        $middlewareMethod=($this->after===false) ? 'handle' : 'after';

        // the app instance is a global application example,
        // and a hash is loaded as this hash.
        $middleware->setMiddleware();

        //When your application is requested, the middleware classes are running before all bootstrapper executables.
        //Thus, if you make http request your application, you can verify with an intermediate middleware layer
        //and throw an exception.
        $resolveServiceMiddleware = $this->app['container']->middlewareClass->{$middlewareMethod}();
        $this->serviceMiddleware($middleware,$resolveServiceMiddleware);

    }

    /**
     * @param $middlewareInstance MiddlewareGlobalInstance
     * @param array $middleware
     */
    private function serviceMiddleware($middlewareInstance,$middleware=array())
    {
        //It will be run individually according to the rules of
        //the middleware classes specified for the service middleware middleware.
        foreach($middleware as $middleVal=>$middleKey){

            //middleware with capital letters
            $middlewareName = ucfirst($middleVal);

            //middleware and exclude class instances
            $excludeClass = $this->app['container']->excludeClass;
            $middlewareClass = $this->app['container']->middlewareClass;

            //middleware definitions.
            $this->middleware['namespace']          = app()->namespace()->middleware().'\\'.$middlewareName;
            $this->middleware['key']                = $middleKey;
            $this->middleware['class']              = $middlewareClass;
            $this->middleware['middlewareName']     = $middleVal;

            //middleware class for service middleware
            //it will be handled according to the following rule.
            //The exclude class will return a callback and allocate the result as bool to the exclude variable.
            //If the exclude variable is true then the middleware will be run.
            $excludeClass->exclude($this->middleware,function($exclude) use ($middlewareInstance,$middleVal){

                if($exclude){

                    //The condition of a specific statement to be handled
                    if($this->checkNamespaceAndSpecificCondition()){
                        $middlewareInstance->pointer($middleVal);
                        $this->app->resolve($this->middleware['namespace'])->handle();
                    }
                }
            });
        }
    }

    /**
     * @param $key
     * @return bool
     */
    private function specificMiddlewareCondition($key)
    {
        //If the all option is present,
        //it is automatically injected into all services for the middleware application.
        if($key==="all") return true;

        //service middleware key
        //if it is array,check odds
        if(is_array($key)){

            //If the user definition specified in the middleware key is an array,
            //then the middleware is conditioned and the services are individually checked according to
            //the degree of conformity with the middlewareOdds method and
            //the middleware is executed under the specified condition.
            $checkOdds=Utils::strtolower($this->middlewareKeyOdds()[count($key)]);
            if(Utils::isArrayEqual($key,$checkOdds)) return true;
        }

        //return false
        return false;
    }

    /**
     * @return array
     */
    private function middlewareKeyOdds()
    {
        return [
          1=>[endpoint],
          2=>[endpoint,method],
          3=>[endpoint,method,$this->app['container']->httpMethod]
        ];
    }

    /**
     * @return bool
     */
    private function checkNamespaceAndSpecificCondition()
    {
        return Utils::isNamespaceExists($this->middleware['namespace']) && $this->specificMiddlewareCondition($this->middleware['key']);
    }
}