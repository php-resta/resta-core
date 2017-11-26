<?php

namespace Resta\Response;

use Resta\ApplicationProvider;
use Resta\Utils;

class ResponseApplication extends ApplicationProvider {

    /**
     * @var array
     */
    public $outputter=[
        'json'=>'Resta\Response\JsonOutputter'
    ];

    /**
     * @method handle
     * @return mixed
     */
    public function handle(){

        return $this->makeBind($this->outPutter())->handle();
    }

    /**
     * @method getResponseKind
     * @return mixed
     */
    public function getResponseKind(){

        return $this->app->kernel()->instanceController->response;
    }

    /**
     * @method outPutter
     * @return mixed
     */
    public function outPutter(){

        return $this->outputter[$this->getResponseKind()];
    }
}