<?php

namespace Resta\Services;

use Resta\Support\Utils;
use Store\Services\RequestClient;
use Resta\Contracts\HandleContracts;

class Request extends RequestClient implements HandleContracts
{
    /**
     * @var array $origin
     */
    protected $origin=[];

    /**
     * @var array $inputs
     */
    protected $inputs=[];

    /**
     * @var array $except
     */
    protected $except=[];

    /**
     * @var $capsule
     */
    protected $capsule;

    /**
     * @var $method
     */
    protected $method;

    /**
     * RequestClient constructor.
     */
    public function __construct()
    {
        //We assign httpMethod constant to property to method name.
        $this->method=appInstance()->httpMethod();

        // if we leave the request process to the application side,
        // then in this case we refer to the requestClient object in
        // the services section of the store directory.
        (property_exists($this,'app') && $this->app) ? parent::handle() : $this->handle();
    }

    /**
     * @return mixed
     */
    private function autoValidate($validate)
    {
        foreach ($this->{$validate} as $object=>$datas){
            if(Utils::isNamespaceExists($object)){
                $getObjectInstance = app()->makeBind($object);
                foreach ($datas as $dataKey=>$data){
                    if(is_numeric($dataKey) && method_exists($getObjectInstance,$data)){
                        if(isset($this->origin[$data])){
                            if(!is_array($this->origin[$data])){
                               $this->origin[$data] = array($this->origin[$data]);
                            }
                            foreach ($this->origin[$data] as $originData){
                                $getObjectInstance->{$data}($originData);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @return void|mixed
     */
    private function checkHttpMethod()
    {
        //get http method
        $method=$this->method;

        // Determines which HTTP method
        // the request object will be exposed to.
        if($this->checkProperties('http')){

            // if the current http method does not exist
            // in the http object, the exception will be thrown.
            if(!in_array($method,$this->http)){

                //exception batMethodCall
                exception()->badMethodCall(
                    'Invalid http method process for '.class_basename($this).'.That is accepted http methods ['.implode(",",$this->http).'] ');
            }
        }
    }

    /**
     * @param $properties
     * @return bool
     */
    private function checkProperties($properties)
    {
        // from the properties of the object properties to
        // the existing variables, control the array and at least one element.
        return (property_exists($this,$properties)
            && is_array($this->{$properties}) && count($this->{$properties})) ? true : false;
    }

    /**
     * @param $except
     * @return $this
     */
    public function except($except)
    {
        // the except parameter is a callable value.
        if(is_callable($except)){
            $call=call_user_func_array($except,[$this]);
            $except=$call;
        }

        // except with the except exceptions property
        // and then assigning them to the inputs property.
        $this->except=array_merge($this->except,$except);
        $this->inputs=array_diff_key($this->inputs,array_flip($this->except));

        return $this;
    }

    /**
     * @return void|mixed
     */
    private function expectedInputs()
    {
        // expected method is executed.
        // this method is a must for http method values to be found in this property.
        if($this->checkProperties('expected')){

            // if the expected values are not found in the inputs array,
            // the exception will be thrown.
            foreach ($this->expected as $expected){
                if(!isset($this->inputs[$expected])){
                    exception()->unexpectedValue('You absolutely have to send the value '.$expected.' for request object');
                }
            }
        }
    }

    /**
     * @return void|mixed
     */
    private function generatorManager()
    {

        // check the presence of the generator object
        // and operate the generator over this object.
        if($this->checkProperties('auto_generators')){
            $this->generators = array_merge($this->generators,$this->auto_generators);
        }

        // check the presence of the generator object
        // and operate the generator over this object.
        if($this->checkProperties('generators')){
            $this->generatorMethod($this->generators);
        }


    }

    /**
     * @param $generators
     */
    private function generatorMethod($generators)
    {


        //generator array object
        foreach ($generators as $generator){

            //generator method name
            $generatorMethodName=$generator.'generator';

            // if the generator method is present,
            // the fake value is assigned.
            if(method_exists($this,$generatorMethodName)){

                //fake registration
                if(!isset($this->inputs[$generator])){
                    $this->{$generator}=$this->{$generatorMethodName}();
                    $this->inputs[$generator]=$this->{$generatorMethodName}();
                }

                $this->registerRequestInputs($generator);
            }
        }
    }

    /**
     * @return array
     */
    protected function get()
    {
        return $this->inputs;
    }

    /**
     * @return array
     */
    private function getClientObjects()
    {
        return array_diff_key($this->getObjects(),['inputs'=>[]]);
    }

    /**
     * @return array
     */
    private function getObjects()
    {
        return get_object_vars($this);
    }

    /**
     * @return void
     */
    public function handle()
    {
        //get http method
        $method=$this->method;

        //we record the values ​​
        //that coming with the post.
        $this->initClient($method);

        // we update the input values ​​after
        // we receive and check the saved objects.
        $this->setClientObjects();

        // we add our user-side properties for the request object,
        // and on this we will set all the request object properties
        // that may be useful for the application.
        $this->requestProperties();
    }

    /**
     * @method initClient
     * @param $method
     * @return void
     */
    private function initClient($method)
    {
        // we use the http method to write
        // the values to the inputs and origin properties.
        foreach($method() as $key=>$value){

            //inputs and origin properties
            $this->inputs[$key]=$value;
            $this->origin[$key]=$value;
        }
    }

    /**
     * @return void|mixed
     */
    private function requestProperties()
    {
        // if a fake method is defined and it is not in
        // the context of any key method when access is granted,
        // it can be filled with fake method.
        $this->generatorManager();

        // contrary to capsule method,
        // expected values must be in the key being sent.
        $this->expectedInputs();

        // this method determines
        // how the request object will be requested,
        $this->checkHttpMethod();

        // it passes all keys that are sent through
        // a validation method on the user side.
        $this->validation();
    }

    /**
     * @return mixed
     */
    private function setClientObjects()
    {
        $clientObjects = $this->getClientObjects();

        // we update the input values ​​after
        // we receive and check the saved objects.
        foreach ($clientObjects as $key=>$value){

            if(isset($clientObjects['origin'][$key])){

                $this->{$key} = $clientObjects['origin'][$key];
                $this->inputs[$key] = $this->{$key};

                // the request update to be performed using
                // the method name to be used with the http method.
                $this->registerRequestInputs($key);
            }
        }
    }

    /**
     * @param $key
     */
    private function registerRequestInputs($key)
    {
        // the method name to be used with
        // the http method.
        $requestMethod=$this->method.''.ucfirst($key);

        // the request update to be performed using
        // the method name to be used with the http method.
        $this->setRequestInputs($requestMethod,$key);

        // the request update to be performed using
        // the method name to be used without the http method.
        $this->setRequestInputs($key,$key);
    }

    /**
     * @param $method
     * @param $key
     */
    private function setRequestInputs($method,$key)
    {
        if(method_exists($this,$method)){

            if(is_array($this->inputs[$key])){

                $inputKeys = $this->inputs[$key];

                $this->inputs[$key] = [];
                foreach ($inputKeys as $input){

                    $this->{$key} = $input;
                    $keyMethod=$this->{$method}();
                    $this->inputs[$key][]=$keyMethod;
                }
            }
            else{
                $keyMethod=$this->{$method}();
                $this->inputs[$key]=$keyMethod;
            }
        }
    }

    /**
     * @return void
     */
    private function validation()
    {
        if(property_exists($this,'autoObjectValidate') && is_array($this->autoObjectValidate) && count($this->autoObjectValidate)){
            $this->autoValidate('autoObjectValidate');
        }
        // we need to find the rule method
        // because we can not validate it.
        if(method_exists($this,'rule')){
            $this->rule();
        }

        // if we only want to make a rule of
        // the specified request object, we will use
        // the rule method with the prefix of the request object.
        $validName=strtolower(str_replace('Request','',class_basename($this))).'Rule';

        //if the specified method exists;
        if(method_exists($this,$validName)){
            $this->{$validName}();
        }
    }
}