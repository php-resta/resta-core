<?php

namespace Resta\Traits;

use Resta\StaticPathModel;
use Resta\Routing\KernelRouterProcess;

trait ResponseOutput {

    /**
     * @param $output
     * @return array
     */
    public function printer($output){

        //if the system throws an exception,
        //we subtract the hateoas extension from the output value.
        $printer=$this->noInExceptionHateoas($output,function() use ($output){

            return array_merge(
                $this->metaAdd(),
                $this->outputCapsule($output)
            );
        });

        //set log for printer
        if(property_exists($this->singleton(),'log')){

            //Log type level
            $type=($this->getSuccess()) ? 'info' : 'error';

            //logger service handler
            return $this->singleton()->loggerService->logHandler($printer,'access',$type);
        }

        //return
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

        //get hateoas class from app config
        $hateoasConfig=StaticPathModel::appConfig(true).'\Hateoas';

        //return
        return array_merge($data,$this->makeBind($hateoasConfig)->handle());
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