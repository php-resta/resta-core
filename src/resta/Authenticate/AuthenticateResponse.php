<?php

namespace Resta\Authenticate;

trait AuthenticateResponse
{
    /**
     * @var array $params
     */
    public $params = [];

    /**
     * @return bool
     */
    private function checkStatus()
    {
        return isset($this->params['status']) && $this->params['status'];
    }

    /**
     * @return void|mixed
     */
    protected function getCheckResult()
    {
        // if the status value is true,
        // we send output generated from the token value.
        if($this->checkStatus()){
            return true;
        }

        //return false
        return false;
    }

    /**
     * @return void|mixed
     */
    protected function getLogoutResult()
    {
        // if the status value is true,
        // we send output generated from the token value.
        if($this->checkStatus()){
            return true;
        }

        //logout exception
       $this->{$this->params['exception']}();
    }

    /**
     * @return array
     */
    protected function getResult()
    {
        $result= [];

        // if the status value is true,
        // we send output generated from the token value.
        if($this->checkStatus()){
            $result['message']                  = 'token success';
            $result['token']                    = $this->params['token'];

            //we send the result value.
            return $result;
        }

        //in the params property, we set the exception type according to the exception value.
        $exceptionType = (isset($this->params['exception'])) ? $this->params['exception'] : 'credentials';

        // if the status is unsuccessful,
        // the direct exception will be thrown away.
        $this->exceptionManager($exceptionType);
    }


}