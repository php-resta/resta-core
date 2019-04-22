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
    protected $middleware = [];

    /**
     * @var array $odds
     */
    protected $odds = [];

    /**
     * after middleware
     *
     * @return void|mixed
     */
    public function after()
    {
        //middleware handle process
        $this->handleMiddlewareProcess('after');
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

        //middleware handle process
        $this->handleMiddlewareProcess();
    }

    /**
     * handle middleware process
     *
     * @param string $method
     * @return void|mixed
     */
    public function handleMiddlewareProcess($method='handle')
    {
        // the app instance is a global application example,
        // and a hash is loaded as this hash.
        $this->setMiddleware();

        //When your application is requested, the middleware classes are running before all bootstrapper executables.
        //Thus, if you make http request your application, you can verify with an intermediate middleware layer
        //and throw an exception.
        $resolveServiceMiddleware = $this->app['middlewareClass']->{$method}();
        $this->serviceMiddleware($resolveServiceMiddleware);
    }

    /**
     * middleware key odds
     *
     * @return array
     */
    public function middlewareKeyOdds()
    {
        $method     = $this->odds['method'] ?? method;
        $endpoint   = $this->odds['endpoint'] ?? endpoint;
        $http       = $this->odds['http'] ?? $this->app['httpMethod'];

        return [
            1=>[$endpoint],
            2=>[$endpoint,$method],
            3=>[$endpoint,$method,$http]
        ];
    }

    /**
     * service middleware
     *
     * @param array $middleware
     */
    public function serviceMiddleware($middleware=array())
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

                        //
                        if($this->app->runningInConsole()) return $this->middleware;

                        // the middleware namespace must have handletraitcontract interface property.
                        // otherwise, middleware will not work.
                        if($this->app->resolve($this->middleware['namespace']) instanceof HandleContracts){
                            $this->app->resolve($this->middleware['namespace'])->handle();
                        }

                    }
                }

                //
                if($this->app->runningInConsole()) return [];

            });
        }
    }

    /**
     * sey middleware key odds
     *
     * @param null $key
     * @param null $value
     */
    public function setKeyOdds($key=null,$value=null)
    {
        if(!is_null($key) && !is_null($value)){
            $this->odds[$key] = $value;
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