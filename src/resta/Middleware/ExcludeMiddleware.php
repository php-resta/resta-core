<?php

namespace Resta\Middleware;

use Resta\Support\Utils;
use Resta\ApplicationProvider;

class ExcludeMiddleware extends ApplicationProvider
{
    /**
     * @var array $excludeList
     */
    protected $excludeList=array();

    /**
     * @var $result
     */
    protected $result=true;

    /**
     * @param $middleware
     * @param callable $callback
     * @return mixed
     */
    public function exclude($middleware,callable $callback)
    {
        //set exclude list for parameters
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
        return Utils::returnCallback($this->result,$callback);
    }

    /**
     * @param $excludeKey
     * @param $excludeVal
     */
    private function excludeProcess($excludeKey,$excludeVal)
    {
        $this->excludeForAll($excludeKey,$excludeVal,function() use ($excludeKey,$excludeVal){

            if($excludeKey==$this->excludeList['middleware']['middlewareName']){
                $this->inArrayExclude($excludeVal);

            }
        });
    }

    /**
     * @return bool
     */
    private function existMethod()
    {
        return Utils::existMethod($this->excludeList['middleware']['class'],'exclude');
    }

    /**
     * @param $exclude
     */
    private function inArrayExclude($exclude)
    {
        if(in_array(Utils::strtolower(endpoint),$exclude)){
            $this->result=false;
        }
    }

    /**
     * @param $excludeKey
     * @param $excludeVal
     * @param callable $callback
     * @return mixed|void
     */
    private function excludeForAll($excludeKey,$excludeVal,callable $callback)
    {
        return ($excludeKey=="all") ? $this->inArrayExclude($excludeVal) : call_user_func($callback);
    }

}