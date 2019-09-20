<?php

namespace Resta\Client;

use Resta\Contracts\ApplicationContracts;

class ClientAnnotationManager extends ClientAnnotationAbstract
{
    /**
     * @var array $exceptionParams
     */
    protected $exceptionParams = [];

    /**
     * @var string $annotation
     */
    protected $annotation;

    /**
     * RequestAnnotationManager constructor.
     * @param ApplicationContracts $app
     * @param $request
     */
    public function __construct(ApplicationContracts $app,$request)
    {
        parent::__construct($app);

        $this->setReflection($request);

        $this->getInputs();
    }

    /**
     * check annotation
     *
     * @param $method
     * @param $key
     * @return mixed|void
     */
    public function annotation($method,$key)
    {
        //set annotation value with getting reflection
        $reflection = $this->getReflection('reflection')->reflectionMethodParams($method);
        $this->annotation = $reflection->document;

        //get remove from request object
        $this->getRemove($key);

        //get exception values from request object
        $this->getException($key);

        //get regex from request object
        $this->getRegex($key);

        //get regex from request object
        $this->getRules($key);
    }

    /**
     * catch exception from regex method
     *
     * @param string $key
     * @param null|string $data
     */
    private function catchException($key,$data)
    {
        if(isset($this->exceptionParams[$key])){

            //get key params for exception params
            $keyParams = ($this->exceptionParams[$key]['params']) ?? [];

            //catch exception
            exception($this->exceptionParams[$key]['name'],$keyParams)
                ->unexpectedValue($this->exceptionParams[$key]['name'].' input value is not valid as format ('.$data.')');
        }
        else{
            //catch exception
            exception()->unexpectedValue($key.' input value is not valid as format ('.$data.')');
        }
    }

    /**
     * get request exception from annotation
     *
     * @param $key
     */
    private function getException($key)
    {
        if(preg_match('@exception\((.*?)\)\r\n@is',$this->annotation,$exception)){

            $exceptionSpaceExplode = explode(" ",$exception[1]);

            foreach ($exceptionSpaceExplode as $exceptions){
                $exceptionsDotExplode = explode(":",$exceptions);
                $this->exceptionParams[$key][$exceptionsDotExplode[0]] = $exceptionsDotExplode[1];
            }

            if(isset($this->exceptionParams[$key]['params'])){

                $paramsCommaExplode = explode(",",$this->exceptionParams[$key]['params']);
                unset($this->exceptionParams[$key]['params']);

                foreach ($paramsCommaExplode as $params){
                    $paramsEqualExplode = explode("=",$params);
                    if(isset($paramsEqualExplode[0]) && isset($paramsEqualExplode[1])){
                        $this->exceptionParams[$key]['params'][$paramsEqualExplode[0]] = $paramsEqualExplode[1];
                    }
                }
            }
        }
    }

    /**
     * get regular expression from request object
     *
     * @param $key
     */
    private function getRegex($key)
    {
        // with the method based regex annotation,
        // we check the rule definition for our requests.
        if(preg_match('@regex\((.*?)\)\r\n@is',$this->annotation,$regex)){
            if(isset($this->inputs[$key])){

                // for the definition of rules,
                // our inputs can be array and in this case we offer array option for user comfort.
                if(is_array($this->inputs[$key])){

                    foreach ($this->inputs[$key] as $this->inputsKey => $this->inputsValue){

                        if(is_array($this->inputsValue)){

                            foreach ($this->inputsValue as $inputsValueKey => $inputsValueItem){
                                if(!preg_match('@'.$regex[1].'@is',$inputsValueItem)){
                                    $this->catchException($key,$regex[1]);
                                }
                            }

                        }
                        else{
                            if(!preg_match('@'.$regex[1].'@is',$this->inputsValue)){
                                $this->catchException($key,$regex[1]);
                            }
                        }

                    }
                }
                else{

                    // we control the regex rule that evaluates when only string arrives.
                    if(!preg_match('@'.$regex[1].'@is',$this->inputs[$key])){
                        $this->catchException($key,$regex[1]);
                    }
                }
            }
        }
    }

    /**
     * get remove regex pattern with request object
     *
     * @param string $key
     * @return void|mixed
     */
    private function getRemove($key)
    {
        if(preg_match('@remove\((.*?)\)\r\n@is',$this->annotation,$remove)){
            if(isset($this->inputs[$key])){
                if(preg_match('@'.$remove[1].'@is',$this->inputs[$key])){
                    unset($this->inputs[$key]);
                }
            }
        }
    }

    /**
     * check rules from request
     *
     * @param $key
     */
    private function getRules($key)
    {
        if(preg_match('@rule\((.*?)\)|rule\((.*?)\)\r\n@is',$this->annotation,$rule)){

            $requestRules = $this->getReflection('rules');

            $rules = explode(":",$rule[1]);
            if(isset($this->inputs[$key]) && !is_array($this->inputs[$key])){
                foreach($rules as $rule){
                    if(isset($requestRules[$rule])){
                        if(!preg_match('@'.$requestRules[$rule].'@',$this->inputs[$key])){
                            exception($rule,['key'=>$key])
                                ->invalidArgument($this->inputs[$key].' input value is not valid for '.$rule.' request rule');
                        }
                    }
                }
            }
            else{

                foreach ($this->inputs[$key] as $key=>$input){

                    if(!is_array($input)){
                        foreach($rules as $rule){
                            if(isset($requestRules[$rule])){
                                if(!preg_match('@'.$requestRules[$rule].'@',$input)){
                                    exception($rule,['key'=>$key])
                                        ->invalidArgument($input.' input value is not valid for '.$rule.' request rule');
                                }
                            }
                        }
                    }
                    else{

                        foreach ($input as $ikey=>$item){
                            foreach($rules as $rule){
                                if(isset($requestRules[$rule])){
                                    if(!preg_match('@'.$requestRules[$rule].'@',$item)){
                                        exception($rule,['key'=>$ikey])
                                            ->invalidArgument($item.' input value is not valid for '.$rule.' request rule');
                                    }
                                }
                            }
                        }

                    }

                }
            }
        }
    }
}