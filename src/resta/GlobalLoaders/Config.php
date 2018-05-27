<?php

namespace Resta\GlobalLoaders;

use Resta\Utils;
use Resta\ApplicationProvider;
use Resta\Traits\InstanceRegister;

class Config extends ApplicationProvider  {

    /**
     * @param array $files
     */
    public function setConfig($files=array()){

        foreach($files as $key=>$file){

            $this->register('appConfig',strtolower($key),[

                'namespace' =>'App\\'.app.'\\'.Utils::getAppVersion(app).'\Config\\'.$key,
                'file'      =>$file
            ]);

        }


    }

}