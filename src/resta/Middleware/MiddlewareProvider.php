<?php

namespace Resta\Middleware;

use Resta\Support\Utils;
use Resta\Contracts\HandleContracts;
use Resta\Foundation\ApplicationProvider;

class MiddlewareProvider extends ApplicationProvider implements HandleContracts
{
    /**
     * @var array $middleware
     */
    protected $middleware = array();

    /**
     * after middleware
     *
     * @return void|mixed
     */
    public function after()
    {
        // the app instance is a global application example,
        // and a hash is loaded as this hash.
        $this->setMiddleware();

        //When your application is requested, the middleware classes are running before all bootstrapper executables.
        //Thus, if you make http request your application, you can verify with an intermediate middleware layer
        //and throw an exception.
        $resolveServiceMiddleware = $this->app['middlewareClass']->after();
        $this->serviceMiddleware($resolveServiceMiddleware);
    }

    /**
     * check namespace and specificCondition
     *
     * @return bool
     */
    private function checkNamespaceAndSpecificCondition()
    {
        return Utils::isNamespaceExists($this->middleware['namespace'])
            && $this->specificMiddlewareCondition($this->middleware['key']);
    }

    /**
     * application middleware handle
     *
     * @return void
     */
    public function handle()
    {
        //set define for middleware
        define('middleware',true);

        // the app instance is a global application example,
        // and a hash is loaded as this hash.
        $this->setMiddleware();

        //When your application is requested, the middleware classes are running before all bootstrapper executables.
        //Thus, if you make http request your application, you can verify with an intermediate middleware layer
        //and throw an exception.
        $resolveServiceMiddleware = $this->app['middlewareClass']->handle();
        $this->serviceMiddleware($resolveServiceMiddleware);

    }

    /**
     * middleware key odds
     *
     * @return array
     */
    private function middlewareKeyOdds()
    {
        return [
            1=>[endpoint],
            2=>[endpoint,method],
            3=>[endpoint,method,$this->app['httpMethod']]
        ];
    }

    /**
     * service middleware
     *
     * @param array $middleware
     */
    private function serviceMiddleware($middleware=array())
    {
        //It will be run individually according to the rules of
        //the middleware classes specified for the service middleware middleware.
        foreach($middleware as $middleVal=>$middleKey){

            // if the keys in the array in the service middleware class represent a class,
            // this value is checked, if it does not represent the class,
            // it is detected as a short name and is searched in the middleware directory.
            if(Utils::isNamespaceExists($middleVal)){
                $middlewareNamespace = $middleVal;
            }
            else{
                $middlewareNamespace = app()->namespace()->middleware().'\\'.ucfirst($middleVal);
            }


            //middleware and exclude class instances
            $excludeClass = $this->app['excludeClass'];
            $middlewareClass = $this->app['middlewareClass'];

            //middleware definitions.
            $this->middleware['namespace']          = $middlewareNamespace;
            $this->middleware['key']                = $middleKey;
            $this->middleware['class']              = $middlewareClass;
            $this->middleware['middlewareName']     = $middleVal;

            //middleware class for service middleware
            //it will be handled according to the following rule.
            //The exclude class will return a callback and allocate the result as bool to the exclude variable.
            //If the exclude variable is true then the middleware will be run.
            $excludeClass->exclude($this->middleware,function($exclude) use ($middleVal){

                if($exclude){

                    //The condition of a specific statement to be handled
                    if($this->checkNamespaceAndSpecificCondition()){
                        $this->pointer($middleVal);

                        // the middleware namespace must have handletraitcontract interface property.
                        // otherwise, middleware will not work.
                        if($this->app->resolve($this->middleware['namespace']) instanceof HandleContracts){
                            $this->app->resolve($this->middleware['namespace'])->handle();
                        }

                    }
                }
            });
        }
    }

    /**
     * register to container for middleware
     *
     * @return void
     */
    private function setMiddleware()
    {
        //We are logging the kernel for the middleware class and the exclude class.
        $this->app->register('middlewareClass',$this->app->resolve(app()->namespace()->serviceMiddleware()));
        $this->app->register('excludeClass',$this->app->resolve(ExcludeMiddleware::class));
    }

    /**
     * specific middleware condition
     *
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
            $checkOdds = Utils::strtolower($this->middlewareKeyOdds()[count($key)]);
            if(Utils::isArrayEqual($key,$checkOdds)) return true;
        }

        //return false
        return false;
    }

    /**
     * register to container for middleware pointer
     *
     * @param $middleValue
     * @return void
     */
    private function pointer($middleValue)
    {
        if(isset($this->app['pointer']['middlewareList'])){

            $middlewareList = $this->app['pointer']['middlewareList'];

            if(is_array($middlewareList)){
                $middlewareList = array_merge($middlewareList,[$middleValue]);
                $this->app->register('pointer','middlewareList',$middlewareList);
            }
        }
        else{
            $this->app->register('pointer','middlewareList',[$middleValue]);
        }
    }
}