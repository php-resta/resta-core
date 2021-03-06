<?php

namespace Resta\Client;

use Resta\Support\Utils;
use Resta\Contracts\HandleContracts;
use Resta\Support\ReflectionProcess;
use ReflectionException as ReflectionExceptionAlias;

/**
 * @property $this auto_capsule
 * @property $this http
 * @property $this autoObjectValidate
 * @property $this requestExcept
 * @property $this expected
 * @property  $this groups
 * @property  $this unity
 */
class Client extends ClientAbstract implements HandleContracts
{
    /**
     * @var array
     */
    protected $capsule = [];

    /**
     * @var null|string
     */
    protected $clientName;

    /**
     * @var array
     */
    protected $except = [];

    /**
     * @var null|string
     */
    protected $method;

    /**
     * @var ReflectionProcess
     */
    protected $reflection;

    /**
     * @var null|ClientHttpManager
     */
    protected $requestHttp;

    /**
     * @var null|array
     */
    protected $clientData;

    /**
     * @var array
     */
    protected $requestData = [];

    /**
     * @var array
     */
    protected $generatorList = [];

    /**
     * Request constructor.
     *
     * @param null|array $clientData
     *
     * @throws ReflectionExceptionAlias
     */
    public function __construct($clientData=null)
    {
        //reflection process
        $this->reflection = app()['reflection']($this);


        //set clientName for client
        $this->setClientName();

        //get http method via request http manager class
        $this->requestHttp = app()->resolve(ClientHttpManager::class,['client'=>$this]);

        //get request client data
        $this->clientData = ($clientData===null) ? $this->requestHttp->resolve() : $clientData;

        //handle request
        $this->handle();
    }

    /**
     * auto validate
     *
     * @param $validate
     */
    private function autoValidate($validate)
    {
        //we get the values ​​to auto-validate.
        foreach ($this->{$validate} as $object=>$datas){

            if(false===Utils::isNamespaceExists($object)){
                return;
            }

            // the auto-validate value must necessarily represent a class.
            // otherwise auto-validate is not used.
            $getObjectInstance = app()->resolve($object);

            // we get the index values,
            // which are called methods of the auto-validate value that represents the class.
            foreach ($datas as $dataKey=>$data){

                // if the methods of the auto-validate class resolved by the container resolve method apply,
                // the process of auto-validate automatic implementation will be completed.
                if(method_exists($getObjectInstance,$dataKey) && is_array($data)){
                    foreach ($data as $dataItem){
                        if(isset($this->origin[$dataItem])){
                            $getObjectInstance->{$dataKey}($this->origin[$dataItem],$this,$dataItem);
                        }

                        if(method_exists($this,$afterMethod = 'after'.ucfirst($dataKey))){
                            $this->{$afterMethod}($this,$dataItem);
                        }
                    }
                }

                // if the methods of the auto-validate class resolved by the container resolve method apply,
                // the process of auto-validate automatic implementation will be completed.
                if(is_numeric($dataKey) && method_exists($getObjectInstance,$data) && isset($this->origin[$data])){
                    if(!is_array($this->origin[$data])){
                        $this->origin[$data] = array($this->origin[$data]);
                    }
                    foreach ($this->origin[$data] as $originData){
                        $getObjectInstance->{$data}($originData,$this,$data);
                    }
                }
            }
        }
    }

    /**
     * capsule inputs
     *
     * @return void|mixed
     */
    private function capsule()
    {
        //a process can be added to the capsule array using the method.
        if(method_exists($this,'capsuleMethod')){
            $this->capsule = array_merge($this->capsule,$this->capsuleMethod());
        }

        // expected method is executed.
        // this method is a must for http method values to be found in this property.
        if($this->checkProperties('capsule')){

            if(property_exists($this,'auto_capsule') && is_array($this->auto_capsule)){
                $this->capsule = array_merge($this->capsule,$this->auto_capsule);
            }

            if($this->checkProperties('groups')){
                $this->capsule = array_merge($this->capsule,$this->groups);

                foreach ($this->capsule as $item) {
                    $groupProcess = $this->groupsProcess($item,true);
                    if(is_array($groupProcess)){
                        $defaultInputs = $this->inputs;
                        $this->inputs = array_merge($this->inputs,$groupProcess);
                    }
                }
            }

            foreach($this->inputs as $input=>$value){

                if($this->checkProperties('capsule') && !in_array($input,$this->capsule)){
                    exception('clientCapsule',['key'=>$input])
                        ->overflow('The '.$input.' value cannot be sent.');
                }
            }

            if(isset($defaultInputs)){
                $this->inputs = $defaultInputs;
            }
        }
    }

    /**
     * check http method
     *
     * @return void|mixed
     */
    private function checkHttpMethod()
    {
        //get http method
        $method = $this->requestHttp->getMethod();

        // Determines which HTTP method
        // the request object will be exposed to.
        if($this->checkProperties('http')){

            // if the current http method does not exist
            // in the http object, the exception will be thrown.
            if(!in_array($method,$this->http)){

                //exception batMethodCall
                exception()->badMethodCall(
                    'Invalid http method process for '.basename($this).'.That is accepted http methods ['.implode(",",$this->http).'] ');
            }
        }
    }

    /**
     * check properties
     *
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
     * register container for request
     *
     * @return mixed|void
     */
    private function containerRegister()
    {
        // we are saving the expected values ​​for the request in container.
        // this record can be returned in exception information.
        app()->register('requestExpected',$this->expected);
    }

    /**
     * get request except
     *
     * @param $except
     * @return $this
     */
    public function except($except)
    {
        // the except parameter is a callable value.
        if(is_callable($except)){
            $call = call_user_func_array($except,[$this]);
            $except = $call;
        }

        // except with the except exceptions property
        // and then assigning them to the inputs property.
        $this->except = array_merge($this->except,$except);
        $this->inputs = array_diff_key($this->inputs,array_flip($this->except));

        return $this;
    }

    /**
     * expected inputs
     *
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

                $expectedValues = [];

                // mandatory expected data for each key can be separated by | operator.
                // this is evaluated as "or".
                foreach($expectedData = explode("|",$expected) as $inputs){

                    // we should do key control for group format.
                    // this process will allow us to perform key control for 2D array correctly.
                    $this->groupsProcess($inputs);

                    if(!isset($this->inputs[$inputs])){
                        $expectedValues[$inputs] = $inputs;
                    }
                }

                // if the expectedData and expectedValues ​​
                // array are numerically equal to the expected key, the exception is thrown.
                if(count($expectedData)===count($expectedValues)){
                    exception('clientExpected',['key'=>$expected])
                        ->unexpectedValue('You absolutely have to send the value '.implode(" or ",$expectedValues).' for request object');
                }
            }
        }
    }

    /**
     * generator manager
     *
     * @throws ReflectionExceptionAlias
     */
    private function generatorManager()
    {
        // check the presence of the generator object
        // and operate the generator over this object.
        if($this->checkProperties('auto_generators')){
            $generators = $this->getAutoGenerators();
        }

        // check the presence of the generator object
        // and operate the generator over this object.
        if($this->checkProperties('generators')){
            $generators = array_merge(isset($generators) ? $generators: [],$this->getGenerators());
        }

        if(isset($generators)){
            $this->generatorMethod($generators);
        }
    }

    /**
     * generator method
     *
     * @param $generators
     *
     * @throws ReflectionExceptionAlias
     */
    private function generatorMethod($generators)
    {
        //generator array object
        foreach ($generators as $generator){

            //generator method name
            $generatorMethodName = $generator.'Generator';

            // if the generator method is present,
            // the fake value is assigned.
            if(method_exists($this,$generatorMethodName)){

                //fake registration
                if(!isset($this->inputs[$generator])){

                    $generatorMethodNameResult = $this->{$generatorMethodName}();

                    if(!is_null($generatorMethodNameResult)){
                        $this->{$generator} = $calledGenerator = $generatorMethodNameResult;
                        $this->inputs[$generator] = $calledGenerator;
                        $this->generatorList[] = $generator;
                    }
                }
                else {

                    if($this->checkProperties('auto_generators_dont_overwrite')
                        && in_array($generator,$this->getAutoGeneratorsDontOverwrite())){
                        $this->{$generator} = $calledGenerator = $this->{$generatorMethodName}();
                        $this->inputs[$generator] = $calledGenerator;
                        $this->generatorList[] = $generator;
                    }

                    if($this->checkProperties('generators_dont_overwrite')
                        && in_array($generator,$this->getGeneratorsDontOverwrite())){
                        $this->{$generator} = $calledGenerator = $this->{$generatorMethodName}();
                        $this->inputs[$generator] = $calledGenerator;
                        $this->generatorList[] = $generator;
                    }

                }

                $this->registerRequestInputs($generator);
            }
        }
    }

    /**
     * get client name for request
     *
     * @return string
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * we should do key control for group format.
     * this process will allow us to perform key control for 2D array correctly.
     *
     * @param null $key
     * @param null $callback
     * @return mixed|void
     */
    public function groupsProcess($key=null,$callback=null)
    {
        if(property_exists($this,'groups') && is_array($this->groups)){

            $clientObjects = $this->getClientObjects();

            foreach ($this->groups as $group){

                if(true === $callback){
                    if(isset($clientObjects['origin'][$key])){
                        return $clientObjects['origin'][$key];
                    }
                    return [];
                }

                if(isset($clientObjects['origin'][$group][$key])){

                    $this->{$key} = $clientObjects['origin'][$group][$key];
                    $this->inputs[$key] = $this->{$key};

                    if(is_callable($callback)){
                        call_user_func_array($callback,[$key]);
                    }
                }
            }
        }

        return [];
    }

    /**
     * request handle
     *
     * @return mixed|void
     *
     * @throws ReflectionExceptionAlias
     */
    public function handle()
    {
        //set container for request
        $this->containerRegister();

        //we record the values ​​
        //that coming with the post.
        $this->initClient();

        if(method_exists($this,'eventBefore')){
            $this->eventBefore();
        }

        // this method determines
        // how the request object will be requested,
        $this->checkHttpMethod();

        // get capsule as mandatory values
        $this->capsule();

        // if a fake method is defined and it is not in
        // the context of any key method when access is granted,
        // it can be filled with fake method.
        $this->generatorManager();

        // contrary to capsule method,
        // expected values must be in the key being sent.
        $this->expectedInputs();

        // contrary to unity method,
        // accepts only one of the elements in the array in the client data sent.
        $this->unity();

        // we update the input values ​​after
        // we receive and check the saved objects.
        $this->setClientObjects();

        // it passes all keys that are sent through
        // a validation method on the user side.
        $this->validation();

        // the values ​​specified in request except property
        // are subtracted from all input values.
        $this->requestExcept();

        if(app()->has('clientRequestInputs')){
            app()->terminate('clientRequestInputs');
        }

        app()->register('clientRequestInputs',$this->inputs);
    }

    /**
     * accepts only one of the elements in the array in the client data sent.
     *
     * @return void
     */
    private function unity()
    {
        // unity method is executed.
        if($this->checkProperties('unity')){

            $list = [];

            foreach ($this->unity as $unity){
                if(isset($this->inputs[$unity])){
                    $list[] = $unity;
                }
            }

            if(count($list)>1){
                exception('clientUnityExpected',['key'=>implode(',',$this->unity)])
                    ->invalidArgument('clientUnityExpected');
            }
        }
    }

    /**
     * get init client
     *
     * @return void
     */
    private function initClient()
    {
        // we use the http method to write
        // the values to the inputs and origin properties.
        foreach($this->clientData as $key=>$value){

            //inputs and origin properties
            $this->inputs[$key] = $value;
            $this->origin[$key] = $value;
        }
    }

    /**
     * the values ​​specified in request except property
     * are subtracted from all input values.
     *
     * @return mixed|void
     */
    private function requestExcept()
    {
        if(property_exists($this,'requestExcept') && is_array($this->requestExcept)){
            foreach ($this->requestExcept as $item){
                if(isset($this->inputs[$item])){
                    unset($this->inputs[$item]);
                }
            }
        }
    }

    /**
     * set client name for client resolver
     *
     * @param null|string $clientName
     * @return void|mixed
     */
    public function setClientName($clientName=null)
    {
        if(!is_null($clientName) && is_string($clientName)){
            return $this->clientName = $clientName;
        }

        if(!is_null(Utils::trace(0)) && isset(Utils::trace(0)['object'])){
            $backTrace = Utils::trace(0)['object'];

            if(property_exists($backTrace,'clientName')){
                $this->clientName = $backTrace->clientName;
            }
        }
    }

    /**
     * set client objects
     *
     * @throws ReflectionExceptionAlias
     */
    private function setClientObjects()
    {
        $clientObjects = $this->getClientObjects();

        // we update the input values ​​after
        // we receive and check the saved objects.
        foreach ($clientObjects as $key=>$value){

            // we should do key control for group format.
            // this process will allow us to perform key control for 2D array correctly.
            $this->groupsProcess($key,function($key){
                $this->registerRequestInputs($key);
                unset($this->inputs[$key]);
            });

            if(!in_array($key,$this->generatorList) && isset($clientObjects['origin'][$key])){

                $this->{$key} = $clientObjects['origin'][$key];
                $this->inputs[$key] = $this->{$key};

                // the request update to be performed using
                // the method name to be used with the http method.
                $this->registerRequestInputs($key);
            }
        }

    }

    /**
     * register request inputs
     *
     * @param $key
     *
     * @throws ReflectionExceptionAlias
     */
    private function registerRequestInputs($key)
    {
        // the method name to be used with
        // the http method.
        $requestMethod = $this->requestHttp->getMethod().''.ucfirst($key);

        // the request update to be performed using
        // the method name to be used with the http method.
        $this->setRequestInputs($requestMethod,$key);

        // the request update to be performed using
        // the method name to be used without the http method.
        $this->setRequestInputs($key,$key);
    }

    /**
     * set request inputs
     *
     * @param $method
     * @param $key
     *
     * @throws ReflectionExceptionAlias
     */
    private function setRequestInputs($method,$key)
    {
        if(!in_array($key,$this->generatorList) && method_exists($this,$method) && $this->reflection->reflectionMethodParams($method)->isProtected){

            //check annotations for method
            $annotation = app()->resolve(ClientAnnotationManager::class,['request'=>$this]);
            $annotation->annotation($method,$key);

            if(isset($this->inputs[$key]) && is_array($this->inputs[$key])){

                $inputKeys = $this->inputs[$key];

                $this->inputs[$key] = [];
                foreach ($inputKeys as $ikey=>$input){

                    $this->{$key}[$ikey]        = $input;
                    $keyMethod                  = $this->{$method}();
                    $this->inputs[$key]         = $keyMethod;
                }
            }
            else{
                if(isset($this->inputs[$key])){
                    $keyMethod = $this->{$method}();
                    $this->inputs[$key] = $keyMethod;
                }

            }
        }
    }

    /**
     * validation for request
     *
     * @return void
     */
    private function validation()
    {
        // the auto object validate property is the property
        // where all of your request values ​​are automatically validated.
        /** @noinspection PhpParamsInspection */
        if(property_exists($this,'autoObjectValidate')
            && is_array($this->autoObjectValidate) && count($this->autoObjectValidate)){
            $this->autoValidate('autoObjectValidate');
        }
    }
}