<?php

namespace Resta\Middleware;

use Resta\Support\Utils;
use Resta\Foundation\ApplicationProvider;

class ExcludeMiddleware extends ApplicationProvider
{
    /**
     * @var array $excludeList
     */
    protected $excludeList = array();

    /**
     * @var $result
     */
    protected $result;

    /**
     * @var $middleware
     */
    protected $middleware;

    /**
     * middleware exclude
     *
     * @param $middleware
     * @param callable $callback
     * @return mixed
     */
    public function exclude($middleware,callable $callback)
    {
        $this->result = true;

        $this->middleware = $middleware;

        //set exclude list for parameters
        $this->excludeList['callback'] = $callback;
        $this->excludeList['middleware'] = $middleware;

        //if there is exclude method
        //in service middleware class
        if($this->existMethod()){

            //call exclude method
            /**
             * @var $serviceMiddleware \Resta\Contracts\ServiceMiddlewareManagerContracts
             */
            $serviceMiddleware = $middleware['class'];
            $excludes = $serviceMiddleware->exclude();

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

            if($excludeKey == $this->excludeList['middleware']['middlewareName']){
                $this->result = true;
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
        foreach($exclude as $item){
            if(in_array($item,$this->middleware['odds'])){
                $this->result = false;
            }
        }
    }

    /**
     * @param $excludeKey
     * @param $excludeVal
     * @param callable $callback
     * @return mixed
     */
    private function excludeForAll($excludeKey,$excludeVal,callable $callback)
    {
        return ($excludeKey=="all") ? $this->inArrayExclude($excludeVal) : call_user_func($callback);
    }

}