<?php

namespace Resta\Services\Controller;

use Resta\StaticPathModel;
use Resta\Utils;

class Controller
{
    /**
     * @var $namespace
     */
    protected $namespace;

    /**
     * @var $reflection
     */
    protected $reflection;

    /**
     * @var $request
     */
    protected $request;

    /**
     * Controller constructor.
     * @param $namespace
     */
    public function __construct($namespace)
    {
        $this->namespace = $namespace;

        $this->request();
    }

    /**
     * @param $method
     * @return bool|string
     * @throws \ReflectionException
     */
    public function getParameters($method)
    {
        $list = [];
        $list['define'] = '';
        $list['route'] = [''];

        $reflection = new \ReflectionMethod($this->namespace,$method);

        $doc = $reflection->getDocComment();

        if(preg_match('@#define(.*?)\r\n@is',$doc,$define)){

            if(isset($define[1])){
                $list['define'] = trim($define[1]);
            }
        }

        foreach ($reflection->getParameters() as $parameter)
        {
            if($parameter->getName()=="route"){
                $list['route']=$parameter->getDefaultValue();
            }
        }

        return $list;
    }



    /**
     * @return $this
     */
    private function request()
    {
        $this->request = app()->makeBind($this->namespace);

        return $this;
    }

    /**
     * @return array
     */
    public function getControllerMethods()
    {
        $methods = get_class_methods($this->request);

        $list = [];

        foreach ($methods as $method){
            if(preg_match('@.*'.StaticPathModel::$methodPrefix.'@is',$method)){

                $methodName = str_replace(StaticPathModel::$methodPrefix,'',$method);

                foreach (Utils::getHttpMethods() as $httpMethod){

                    if(preg_match('@'.$httpMethod.'.*@is',$methodName)){
                        $list[$httpMethod]=[
                            'short' => strtolower(str_replace($httpMethod,'',$methodName)),
                            'default'=> $method
                        ];
                    }
                }
            }
        }

        return $list;
    }
}