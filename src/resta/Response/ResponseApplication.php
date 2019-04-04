<?php

namespace Resta\Response;

use Resta\Support\Utils;
use Resta\Support\ClosureDispatcher;

class ResponseApplication extends ResponseOutput
{
    /**
     * @return mixed
     */
    private function appResponseType()
    {
        //get controller instance
        $controllerInstance = $this->getControllerInstance();

        //If our endpoint is provided without auto service,
        //we get the response object from the existing kernel object without resampling our service base class.
        //In this case we need to instantiate the service base class of the existing project for
        //the auto service to be called.
        return ClosureDispatcher::bind($controllerInstance)->call(function() use($controllerInstance){

            if(property_exists($controllerInstance,'response')){
                return $controllerInstance->response;
            }

            //For auto service, service base is instantiate and response object is accessed.
            return config('app.response');
        });
    }

    /**
     * @return mixed
     */
    private function getControllerInstance()
    {
        //we get the instanceController object from the router.
        return core()->instanceController;
    }

    /**
     * @return mixed
     */
    private function getResponseKind()
    {
        //we get the response type by checking the instanceController object from the router.
        //Each type of response is in the base class in project directory.
        return ($this->getControllerInstance()===null) ? core()->responseType :
            $this->appResponseType();
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        //definition for singleton instance
        define ('responseApp',true);

        //get out putter for response
        $formatter = $this->formatter();

        //if out putter is not null
        if(Utils::isNamespaceExists($formatter)){
            
            //We resolve the response via the service container
            //and run the handle method.
            return app()->resolve($formatter)->{$this->getResponseKind()}($this->getOutPutter());
        }
    }

    /**
     * @return \Resta\Config\ConfigProcess
     */
    private function formatter()
    {
        //we get and handle the adapter classes in
        //the output array according to the base object.
        return config('response.outputter.formatter');
    }

    /**
     * @param array $data
     * @return array
     */
    public function outputFormatter($data=array())
    {
        $dataCapsule = config('response.data');

        return array_merge(
            config('response.meta'),
            [$dataCapsule=>$data]
        );
    }
}