<?php

namespace Resta\Traits;

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
     * @param $data
     * @return array
     */
    private function outputCapsule($data){

        return [
            'data'=>$data
        ];
    }

    /**
     * @return array
     */
    private function metaAdd(){

        return [

            'success'=>true,
            'status'=>200,
        ];
    }
}