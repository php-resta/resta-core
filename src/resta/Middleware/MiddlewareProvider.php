<?php

namespace Resta\Middleware;

use Resta\Support\Utils;
use Resta\Contracts\HandleContracts;
use Resta\Foundation\ApplicationProvider;
use Resta\Support\AppData\ServiceMiddlewareManager;
use Resta\Contracts\ServiceMiddlewareManagerContracts;

class MiddlewareProvider extends ApplicationProvider implements HandleContracts
{
    /**
     * @var array $odds
     */
    protected $odds = [];

    /**
     * @var array $show
     */
    protected $show = [];

    /**
     * @var array $middleware
     */
    protected $middleware = [];

    /**
     * @var $serviceMiddleware
     */
    protected $serviceMiddleware;

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
     * get resolve service middleware
     *
     * @return mixed
     */
    public function getResolveServiceMiddleware()
    {
        if(!is_null($this->serviceMiddleware)){
            return $this->app->resolve($this->serviceMiddleware);
        }

        return $this->app['middlewareClass'];
    }

    /**
     * get assigned service middleware
     *
     * @return mixed
     */
    public function getServiceMiddleware()
    {
        return $this->serviceMiddleware;
    }

    /**
     * get show data for middleware
     *
     * @return array
     */
    public function getShow()
    {
        return $this->show;
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

        // the middleware class must be subject to
        // the ServiceMiddlewareManagerContracts interface rule to be implemented.
        if(!$this->getResolveServiceMiddleware() instanceof ServiceMiddlewareManagerContracts){
            exception()->badMethodCall('Service middleware does not have ServiceMiddlewareManagerContracts');
        }

        //When your application is requested, the middleware classes are running before all bootstrapper executables.
        //Thus, if you make http request your application, you can verify with an intermediate middleware layer
        //and throw an exception.
        $resolveServiceMiddleware = $this->getResolveServiceMiddleware()->{$method}();
        return $this->serviceMiddleware($resolveServiceMiddleware);
    }

    /**
     * middleware key odds
     *
     * @param $key null
     * @return array
     */
    public function middlewareKeyOdds($key=null)
    {
        // identifying constants for the middleware layer.
        // with the property of the user, the user is free to determine the constants that the middleware layer wants.
        $method     = $this->odds['method'] ?? implode("/",$this->app['routeParameters']);
        $endpoint   = $this->odds['endpoint'] ?? endpoint;
        $http       = $this->odds['http'] ?? $this->app['httpMethod'];

        //method can only return fixed.
        if(!is_null($key)){
            if(isset($$key)) return $$key;
        }

        //middleware key odds
        return [
            strtolower($endpoint),
            strtolower($endpoint).'@'.strtolower($method),
            strtolower($endpoint).'@'.strtolower($method).'@'.strtolower($http)
        ];
    }

    /**
     * service middleware
     *
     * @param array $middleware
     */
    public function serviceMiddleware($middleware=array())
    {
        $this->show = [];

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
            $middlewareClass = $this->getResolveServiceMiddleware();

            //middleware definitions.
            $this->middleware['namespace']          = $middlewareNamespace;
            $this->middleware['key']                = $middleKey;
            $this->middleware['class']              = $middlewareClass;
            $this->middleware['middlewareName']     = $middleVal;
            $this->middleware['odds']               = $this->middlewareKeyOdds();

            //middleware class for service middleware
            //it will be handled according to the following rule.
            //The exclude class will return a callback and allocate the result as bool to the exclude variable.
            //If the exclude variable is true then the middleware will be run.
            $excludeClass->exclude($this->middleware,function($exclude) use ($middleVal){

                if($exclude){

                    //the condition of a specific statement to be handled
                    if($this->checkNamespaceAndSpecificCondition()){
                        $this->pointer($middleVal);

                        //directly registered to the middleware name show property.
                        $this->show[] = class_basename($this->middleware['namespace']);

                        // the middleware namespace must have handletraitcontract interface property.
                        // otherwise, middleware will not work.
                        if(false === $this->app->runningInConsole()
                            && $this->app->resolve($this->middleware['namespace']) instanceof HandleContracts){
                            $this->app->resolve($this->middleware['namespace'])->handle();
                        }
                    }
                }

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
        //user-defined middleware constants.
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
        //get service middleware namespace
        $serviceMiddleware = $this->serviceMiddleware ?? app()->namespace()->serviceMiddleware();

        // if the service middleware does not represent a class,
        // then in this case core support is assigned as a class service middleware.
        if(Utils::isNamespaceExists($serviceMiddleware)===false){
            $serviceMiddleware = ServiceMiddlewareManager::class;
        }

        //We are logging the kernel for the middleware class and the exclude class
        $this->app->register('middlewareClass',$this->app->resolve($serviceMiddleware));
        $this->app->register('excludeClass',$this->app->resolve(ExcludeMiddleware::class));
    }

    /**
     * set service middleware
     *
     * @param null $serviceMiddleware
     */
    public function setserviceMiddleware($serviceMiddleware=null)
    {
        if(!is_null($serviceMiddleware)){
            $this->serviceMiddleware = $serviceMiddleware;
        }
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

            //get middleware odd keys
            $odds = $this->middlewareKeyOdds();

            //If the user definition specified in the middleware key is an array,
            //then the middleware is conditioned and the services are individually checked according to
            //the degree of conformity with the middlewareOdds method and
            //the middleware is executed under the specified condition.
            foreach($key as $item){
                if(in_array($item,$odds)){
                    return true;
                }
            }
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