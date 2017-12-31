<?php

namespace Resta\Middleware;

use Resta\ApplicationProvider;
use Resta\StaticPathModel;
use Resta\Utils;

class ApplicationMiddleware extends ApplicationProvider {

    /**
     * @method boot
     * @return mixed
     */
    public function handle(){

        //The app instance is a global application example, and a hash is loaded as this hash.
        $this->singleton()->middlewareGlobalInstance->setAppInstance();

        //When your application is requested, the middleware classes are running before all bootstrapper executables.
        //Thus, if you make http request your application, you can verify with an intermediate middleware layer
        //and throw an exception.
        $resolveServiceMiddleware=$this->makeBind(appMiddlewarePath)->handle();
        $this->serviceMiddleware($resolveServiceMiddleware);

    }

    /**
     * @param array $middleware
     */
    private function serviceMiddleware($middleware=array()){

        //It will be run individually according to the rules of
        //the middleware classes specified for the service middleware middleware.
        foreach($middleware as $middleVal=>$middleKey){

            //middleware class for service middlware
            //it will be handled according to the following rule.
            $middlewareNamespace=middleware.'\\'.ucfirst($middleVal);

            //The condition of a specific statement to be handled
            if(Utils::isNamespaceExists($middlewareNamespace) && $this->specificMiddlewareCondition($middleKey)){
                $this->makeBind($middlewareNamespace)->handle();
            }
        }
    }

    /**
     * @param $key
     * @return bool
     */
    private function specificMiddlewareCondition($key){

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
    private function middlewareKeyOdds(){

        return [
          1=>[endpoint],
          2=>[endpoint,method],
          3=>[endpoint,method,$this->singleton()->httpMethod]
        ];
    }
}