<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;

class Config extends ApplicationProvider  {

    /**
     * register config classess to kernel
     *
     * @param array $files
     */
    public function setConfig($files=array()){

        foreach($files as $key=>$file){

            $this->register('appConfig',strtolower($key),[
                'namespace' =>app()->namespace()->config().'\\'.$key,
                'file'      =>$file
            ]);

        }


    }

}