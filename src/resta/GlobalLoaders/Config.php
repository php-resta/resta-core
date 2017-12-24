<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;
use Resta\Utils;

class Config extends ApplicationProvider  {

    /**
     * @param array $files
     */
    public function setConfig($files=array()){

        foreach($files as $key=>$file){

            $this->singleton()
                    ->appConfig[strtolower($key)]=[

                'namespace' =>'App\\'.app.'\\'.Utils::getAppVersion(app).'\Config\\'.$key,
                'file'      =>$file
            ];
        }

    }

}