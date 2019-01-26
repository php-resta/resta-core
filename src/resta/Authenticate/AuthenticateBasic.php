<?php

namespace Resta\Authenticate;

trait AuthenticateBasic
{
    /**
     * @param callable $callback
     */
    protected function checkParamsViaAvailability($data,callable $callback)
    {
        // if an authenticate is provided via the existing check method,
        // then we return the value of the data that we are checking for with callback help.
        if($this->check() && isset($this->params[$data])){
            return call_user_func_array($callback,[$this->params[$data]]);
        }

        return null;

    }

    /**
     * @param $type
     */
    protected function checkProcessHttpMethod($type)
    {
        $getHttp=(array)$this->getHttp();

        // we will determine whether
        // the http path is correct for this method.
        if(isset($getHttp[$type]) and $getHttp[$type]!==appInstance()->httpMethod()){
            $this->getExceptionForHttp($getHttp[$type]);
        }
    }

    /**
     * @param callable $callback
     */
    protected function checkTokenViaHeaders(callable $callback)
    {
        //get headers
        $headers = headers();

        //get token key from config
        $tokenKey=$this->getTokenKey();

        // if there is a token in the headers,
        // we return the callback.
        if(isset($headers[$tokenKey])){
            return call_user_func_array($callback,[$headers[$tokenKey][0]]);
        }

        //token false
        return false;
    }

}