<?php

namespace Resta\Traits;

use Resta\StaticPathModel;

trait ResponseOutput {

    /**
     * @param $output
     * @return array
     */
    public function printer($output){

        return array_merge(
            $this->metaAdd(),
            $this->outputCapsule($output)
        );
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
}