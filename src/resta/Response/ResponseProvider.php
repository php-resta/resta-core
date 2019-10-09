<?php

namespace Resta\Response;

use Resta\Support\Utils;
use Resta\Support\ClosureDispatcher;
use Resta\Foundation\ApplicationProvider;

class ResponseProvider extends ApplicationProvider
{
    //get response output
    use ResponseOutput;

    /**
     * app response type
     *
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

            // if the client wishes,
            // data can be returned in the supported format.
            if(app()->has('clientResponseType')){
                return app()->get('clientResponseType');
            }

            return config('app.response');
        });
    }

    /**
     * formatter
     *
     * @return \Resta\Config\ConfigProcess
     */
    public function formatter()
    {
        //we get and handle the adapter classes in
        //the output array according to the base object.
        return config('response.outputter.formatter');
    }

    /**
     * get controller instance
     *
     * @return mixed
     */
    private function getControllerInstance()
    {
        //we get the instanceController object from the router.
        return core()->instanceController;
    }

    /**
     * get response kind
     *
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
     * response application handle
     *
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
            $result = app()->resolve($formatter)->{$this->getResponseKind()}($this->getOutPutter());

            $this->app->register('result',$result);
        }
    }

    /**
     * output formatter
     *
     * @param array $data
     * @return array
     */
    public function outputFormatter($data=array(),$outputter='json')
    {
        $dataCapsule = config('response.data');

        return  array_merge(
            config('response.meta'),
            [$dataCapsule=>$data]
        );
    }
}