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
     * The values ​​expected by the server.
     * @var array
     */
    protected $expected=[];

    /**
     * mandatory http method.
     * @var array
     */
    protected $http=[];

    /**
     * AuthenticateRequest constructor.
     * @param $credentials
     */
    public function __construct($credentials) {

        //credentials loop for expected property
        foreach ($credentials as $credential){
            $this->expected[]=$credential;
        }

        parent::__construct();
    }

    /**
     * @param $credentials
     */
    public function credentials($credentials){

        $credentials=[];

        foreach ($this->inputs as $inputKey=>$inputValue){

            if(in_array($inputKey,$this->expected)){
                $credentials[$inputKey]=$inputValue;
            }
        }

        return $credentials;
    }

    /**
     * @return void
     */
    public function rule(){

        //
    }
}