<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;

class Config extends ApplicationProvider
{
    /**
     * @param array $files
     * @return void
     */
    public function setConfig($files=array())
    {
        // we are saving all files in
        // the config directory of each application.
        foreach($files as $key=>$file){

            $this->register('appConfig',strtolower($key),[
                'namespace' =>app()->namespace()->config().'\\'.$key,
                'file'      =>$file
            ]);
        }
    }
}