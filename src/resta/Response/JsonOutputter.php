<?php

namespace Resta\Response;

use Resta\ApplicationProvider;
use Resta\Traits\ResponseOutput;

class JsonOutputter extends ApplicationProvider {

    //get response output
    use ResponseOutput;

    /**
     * @method handle
     * @return string
     */
    public function handle(){

        header('Content-type:application/json;charset=utf-8');
        return json_encode($this->getOutPutter());
    }

    /**
     * @method getOutPut
     * @return mixed
     */
    public function getRouter(){

        return $this->app->kernel()->router;
    }

    /**
     * @method getOutPutter
     * @return mixed
     */
    public function getOutPutter(){

        return $this->printer($this->getRouter());
    }
}