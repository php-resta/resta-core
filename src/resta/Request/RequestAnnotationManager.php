<?php

namespace Resta\Request;

use Resta\Support\ClosureDispatcher;
use Resta\Contracts\ApplicationContracts;
use Resta\Foundation\ApplicationProvider;

class RequestAnnotationManager extends RequestAnnotationAbstract
{
    /**
     * @var array $exceptionParams
     */
    protected $exceptionParams = [];

    /**
     * @var $annotation
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
     * checkt annotations
     *
     * @param $method
     * @param $key
     *
     * @throws \ReflectionException
     */
    public function annotation($method,$key)
    {
        //set annotation value with getting reflection
        $reflection = $this->getReflection('reflection')->reflectionMethodParams($method);
        $this->annotation = $reflection->document;

        //get remove from request object
        $this->getRemove();

        //get exception values from request object
        $this->getException();

        //get regex from request object
        $this->getRegex($method,$key);
    }

    /**
     * catch exception from regex method
     *
     * @param $key
     * @param $data
     */
    private function catchException($key,$data)
    {
        if(isset($this->exceptionParams[$key])){
            $keyParams = ($this->exceptionParams[$key]['params']) ?? [];
            exception($this->exceptionParams[$key]['name'],$keyParams)->unexpectedValue($key.' input value is not valid as format ('.$data.')');
        }
        else{
            exception()->unexpectedValue($key.' input value is not valid as format ('.$data.')');
        }
    }

    /**
     * get request exception from annotation
     *
     * @param $annotation
     */
    private function getException()
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
     * @param $method
     * @param $key
     */
    private function getRegex($method,$key)
    {
        if(preg_match('@regex\((.*?)\)\r\n@is',$this->annotation,$regex)){
            if(isset($this->inputs[$key])){

                if(is_array($this->inputs[$key])){

                    foreach ($this->inputs[$key] as $this->inputsKey=>$this->inputsValue){
                        if(!preg_match('@'.$regex[1].'@is',$this->inputsValue)){
                            $this->catchException($key,$regex[1]);
                        }
                    }
                }
                else{

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
     * @return void|mixed
     */
    private function getRemove()
    {
        if(preg_match('@remove\((.*?)\)\r\n@is',$this->annotation,$remove)){
            if(isset($this->inputs[$key])){
                if(preg_match('@'.$remove[1].'@is',$this->inputs[$key])){
                    unset($this->inputs[$key]);
                }
            }
        }
    }
}