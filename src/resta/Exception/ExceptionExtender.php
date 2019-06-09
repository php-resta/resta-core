<?php

namespace Resta\Exception;

use Resta\Contracts\ApplicationContracts;
use Resta\Foundation\ApplicationProvider;

class ExceptionExtender extends ApplicationProvider
{
    /**
     * @var array
     */
    protected $result;

    /**
     * @var array
     */
    protected $extender = ['request'];

    /**
     * ExceptionExtender constructor.
     * @param ApplicationContracts $app
     * @param array $result
     */
    public function __construct(ApplicationContracts $app,$result=array())
    {
        parent::__construct($app);

        $this->result = $result;

        foreach($this->extender as $item){
            if(method_exists($this,$item)){
                $this->{$item}();
            }
        }
    }

    /**
     * get result for exception
     *
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * get request expected items
     *
     * @return void
     */
    public function request()
    {
        // we will look at the requestExpected container value to show
        // the expected values ​​for the request object in the exception output.
        if(app()->has('requestExpected') && config('app.requestWithError')===true){
            if($requestExpected = app()->get('requestExpected')){
                $this->result['request']['expected'] = $requestExpected;
            }
        }
    }
}