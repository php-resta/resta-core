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

        return [
            'data'=>$data,
            'links'=>[
                'href'              =>$this->request()->getUri(),
                'client_get'        => $this->request()->query->all(),
                'client_post'       =>$this->request()->request->all(),
                'client_header'     =>$this->getClientHeaders()
            ]
        ];
    }

    /**
     * @method getClientHeaders
     * @return array
     */
    private function getClientHeaders(){

        /*** @var $httpHeaders \Store\Config\HttpHeaders */
        //get Client Http Headers
        $httpHeaders=StaticPathModel::$store.'\Config\HttpHeaders';

        $list=[];

        //We only get the objects in the list name to match the header objects
        //that come with the request path to the objects sent by the client
        foreach ($this->request()->headers->all() as $key=>$value) {

            //We separate the header objects sent by the client from all
            //the header fields and send them only to the output
            if(!in_array($key,$httpHeaders::$httpHeaders)){
                $list[$key]=$value;
            }
        }

        //return header list
        return $list;
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