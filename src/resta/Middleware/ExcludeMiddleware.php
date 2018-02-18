<?php

namespace Resta\Middleware;

use Resta\ApplicationProvider;
use Resta\StaticPathModel;
use Resta\Utils;

class ExcludeMiddleware extends ApplicationProvider {

    /**
     * @var $callback
     */
    protected $excludeList=array();

    /**
     * @var $result
     */
    protected $result=true;

    /**
     * @param $middlewareClass
     * @param callable $callback
     */
    public function exclude($middleware,callable $callback){

        //set excludelist for paramteters
        $this->excludeList['callback']=$callback;
        $this->excludeList['middleware']=$middleware;

        //if there is exclude method
        //in service middleware class
        if($this->existMethod()){

            //call exclude method
            $excludes=$middleware['class']->exclude();

            foreach ($excludes as $excludeKey=>$excludeVal){
                $this->excludeProcess($excludeKey,$excludeVal);
            }
        }

        //return true
        return $this->returnCallback($this->result,$callback);
    }

    /**
     * @param $excludeKey
     * @param $excludeVal
     */
    private function excludeProcess($excludeKey,$excludeVal){

        $this->excludeForAll($excludeKey,$excludeVal,function() use ($excludeKey,$excludeVal){

            if($excludeKey==$this->excludeList['middleware']['middlewareName']){
                $this->inArrayExclude($excludeVal);

            }
        });
    }

    /**
     * @param $bool
     * @param $callback
     * @return mixed
     */
    private function returnCallback($bool,$callback){

        return call_user_func_array($callback,[$bool]);
    }

    /**
     * @return string
     */
    private function psrEndpoint(){

        return strtolower(endpoint);
    }

    /**
     * @return bool
     */
    private function existMethod(){

        return method_exists($this->excludeList['middleware']['class'],'exclude');
    }

    /**
     * @param $exclude
     */
    private function inArrayExclude($exclude){

        if(in_array($this->psrEndpoint(),$exclude)){
            $this->result=false;
        }
    }

    /**
     * @param $excludeKey
     * @param $excludeVal
     * @param callable $callback
     * @return mixed|void
     */
    private function excludeForAll($excludeKey,$excludeVal,callable $callback){

        return ($excludeKey=="all") ? $this->inArrayExclude($excludeVal) : call_user_func($callback);
    }

}