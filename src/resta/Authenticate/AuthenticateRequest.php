<?php

namespace Resta\Authenticate;

use Resta\Services\Request as RequestClient;

class AuthenticateRequest extends RequestClient {

    /**
     * With the auto injector,
     * all the indices in the array are executed as methods
     * and the global input is automatically injected.
     *
     * @var array $autoInject
     */
    protected $autoInject=[];

    /**
     * @return array
     */
    public function all(){
        return $this->inputs;
    }

    /**
     * @return void
     */
    public function rule(){

        //
    }

    public function auth(){

    }


}