<?php

namespace Resta\Services;

use Resta\Utils;
use Store\Services\RequestClient;
use Resta\Contracts\HandleContracts;

class Request extends RequestClient implements HandleContracts {

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
    public function __construct() {

        //We assign httpMethod constant to property to method name.
        $this->method=appInstance()->httpMethod();

        // if we leave the request process to the application side,
        // then in this case we refer to the requestClient object in
        // the services section of the store directory.
        (property_exists($this,'app') && $this->app) ? parent::handle() : $this->handle();
    }

    /**
     * @return void
     */
    protected function autoInjection(){

        // we get the autoInject method from
        // the sequence obtained by getObjects.
        $getObjects     = $this->getObjects();
        $autoInject     = $getObjects['autoInject'];

        //if auto inject count is true
        if(count($autoInject)){

            // we provide the auto method with
            // auto value added as prefix to the auto inject method name.
            foreach($autoInject as $key=>$method){

                //prefix auto value
                $autoMethod='auto'.ucfirst($method);

                // if the specified auto method has been created by the user,
                // we are running.
                if(method_exists($this,$autoMethod)){
                    $this->inputs[$method]=$this->{$autoMethod}();
                }
            }
        }
    }

    /**
     * @return void
     */
    private function capsule(){

        //we are in capsule control.
        if($this->checkProperties('capsule')){

            // with the capsule method we ensure
            // that all values are the same as those existing in the capsule.
            // if the value does not exist in capsule, we will throw an exception.
            foreach ($this->inputs as $key=>$value){
                if(!in_array($key,$this->capsule)){
                    exception()->unexpectedValue($key .' input  as value sent is not invalid ');
                }
            }

            // all values are in the capsule but the values sent are too big;
            // in this case we will throw an exception again.
            if(Utils::isArrayEqual(array_keys($this->inputs),$this->capsule)===false){
                exception()->unexpectedValue('the values accepted by the server are not the same with values you sent');
            }
        }
    }

    /**
     * @return void|mixed
     */
    private function checkHttpMethod(){

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
    private function checkProperties($properties){

        // from the properties of the object properties to
        // the existing variables, control the array and at least one element.
        return (property_exists($this,$properties)
            && is_array($this->{$properties}) && count($this->{$properties})) ? true : false;
    }

    /**
     * @param $except
     * @return $this
     */
    public function except($except){

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
    private function expectedInputs(){

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
    private function fakerManager(){

        // the first way in the faker method is to detect
        // the presence of the faker method and if so, to run this method.
        if(method_exists($this,'faker')){
            $this->fakerMethod($this->faker());
        }
        else{

            // in the second case where the faker is not found,
            // check the presence of the faker object
            // and operate the faker over this object.
            if($this->checkProperties('faker')){
                $this->fakerMethod($this->faker);
            }
        }
    }

    /**
     * @param $faker
     * @return void|mixed
     */
    private function fakerMethod($faker){

        //faker array object
        foreach ($faker as $fake){

            //faker method name
            $fakerMethodName=$fake.'Faker';

            // if the faker method is present,
            // the fake value is assigned.
            if(method_exists($this,$fakerMethodName)){

                //fake registration
                if(!isset($this->inputs[$fake])){
                    $this->inputs[$fake]=$this->{$fakerMethodName}();
                }
            }
        }
    }

    /**
     * @return array
     */
    protected function get(){
        return $this->inputs;
    }

    /**
     * @return array
     */
    private function getClientObjects(){
        return array_diff_key($this->getObjects(),['inputs'=>[]]);
    }

    /**
     * @return array
     */
    private function getObjects(){
        return get_object_vars($this);
    }

    /**
     * @return void
     */
    public function handle(){

        //get http method
        $method=$this->method;

        //we record the values ​​
        //that coming with the post.
        $this->initClient($method);

        // we update the input values ​​after
        // we receive and check the saved objects.
        $this->setClientObjects($method);

        // we add our user-side properties for the request object,
        // and on this we will set all the request object properties
        // that may be useful for the application.
        $this->requestProperties();
    }

    /**
     * @param callable $callback
     */
    private function ifCallableRequestMethod($keyMethod,$key,callable $callback){

        if(is_callable($keyMethod) & is_array($this->inputs[$key])){

            foreach ($this->inputs[$key] as $ikey=>$input){

                if(!isset($removeFirstKey)){
                    $this->inputs[$key]=[];
                }
                $this->inputs[$key][$ikey]=$keyMethod($input);
                $removeFirstKey=true;
            }

            return true;
        }

        return call_user_func($callback);
    }

    /**
     * @method initClient
     * @param $method
     * @return void
     */
    private function initClient($method){

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
    private function requestProperties(){

        // contrary to capsule method,
        // expected values must be in the key being sent.
        $this->expectedInputs();

        // this method determines
        // how the request object will be requested,
        $this->checkHttpMethod();

        // mandates the specified keys and
        // prohibits the sending of values other than those values.
        $this->capsule();

        // it passes all keys that are sent through
        // a validation method on the user side.
        $this->validation();

        // allows the user to execute
        // the methods specified by the user.
        $this->autoInjection();

        // if a fake method is defined and it is not in
        // the context of any key method when access is granted,
        // it can be filled with fake method.
        $this->fakerManager();
    }

    /**
     * @param $method
     * @return void|mixed
     */
    private function setClientObjects($method){

        // we update the input values ​​after
        // we receive and check the saved objects.
        foreach ($this->getClientObjects() as $key=>$value){

            if($method($key)!==null){

                $this->inputs[$key]=$value;

                if($value===null){
                    $this->{$key}=$method($key);
                    $this->inputs[$key]=$this->{$key};
                }

                // the method name to be used with
                // the http method.
                $requestMethod=$method.''.ucfirst($key);

                // the request update to be performed using
                // the method name to be used with the http method.
                $this->setRequestInputs($requestMethod,$key);

                // the request update to be performed using
                // the method name to be used without the http method.
                $this->setRequestInputs($key,$key);

            }
        }
    }

    /**
     * @param $method
     * @param $key
     */
    private function setRequestInputs($method,$key){

        if(method_exists($this,$method)){

            $keyMethod=$this->{$method}();

            // if the request objects contain an array value,
            // then we assign the values ​​of the closure object specified by the user in this case.
            $this->ifCallableRequestMethod($keyMethod,$key,function() use($keyMethod,$key){
                $this->inputs[$key]=$keyMethod;
            });
        }
    }

    /**
     * @return void
     */
    private function validation(){

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