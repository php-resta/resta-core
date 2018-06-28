<?php

namespace Resta\Traits;

use Resta\StaticPathModel;
use Resta\Routing\KernelRouterProcess;

trait ResponseOutput {

    /**
     * @var $printer
     */
    protected $printer;

    /**
     * @param $output
     * @return array
     */
    public function printer($output){

        //if the system throws an exception,
        //we subtract the hateoas extension from the output value.
        $this->printer=$this->noInExceptionHateoas($output,function() use ($output){

            return array_merge(
                $this->metaAdd(),
                $this->outputCapsule($output)
            );
        });

        // For the data to be included in the response,
        // we go to the dataIncludedForPrinter method.
        $this->printer=$this->dataIncludedForPrinter($this->printer);

        // If the log feature is available on the kernel,
        // we run the logger process.
        if(isset($this->singleton()->log)){

            // we can run logging after checking
            // the configuration for the logger process in the LoggerService class
            // so that,If logging is not allowed in the main configuration file, we will not log.
            return $this->singleton()->loggerService->checkLoggerConfiguration($this->printer,function($printer){
                return $printer;
            });
        }

        //default printer
        return $this->printer;



    }

    /**
     * @param $printer
     */
    private function dataIncludedForPrinter($printer){

        if(isset(app()->singleton()->controllerWatch)){

            $watch=app()->singleton()->controllerWatch;
            return array_merge($printer,['watch'=>['memory'=>$watch['memory']]]);
        }

        //return printer
        return $printer;

    }

    /**
     * @method outputCapsule
     * @param $data
     * @return array
     */
    private function outputCapsule($data){

        return $this->hateoasCapsule([
            'data'=>$data,
        ]);
    }

    /**
     * @param $output
     * @param callable $callback
     */
    private function noInExceptionHateoas($output,callable $callback){

        if(isset($output['success']) && false===$output['success']){
            return $output;
        }

        return call_user_func($callback);

    }

    /**
     * @param $data array
     * @method hateoasCapsule
     * @return mixed
     */
    private function hateoasCapsule($data=array()){

        //return
        return (config('app.hateoas')) ? array_merge($data,config('hateoas')) : $data;
    }

    /**
     * @method metaAdd
     * @return array
     */
    private function metaAdd(){

        return [
            'success'=>$this->getSuccess(),
            'status'=>$this->getStatus(),
        ];
    }

    /**
     * @method getOutPut
     * @return mixed
     */
    public function getRouter(){

        return $this->app->makeBind(KernelRouterProcess::class)->router();
    }

    /**
     * @method getOutPutter
     * @return mixed
     */
    public function getOutPutter(){

        return $this->printer($this->getRouter());
    }


}