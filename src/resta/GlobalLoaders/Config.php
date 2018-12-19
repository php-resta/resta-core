<?php

namespace Resta\GlobalLoaders;

use Resta\Support\Str;
use Resta\ApplicationProvider;

class Config extends ApplicationProvider
{
    /**
     * @param array $files
     * @return void
     */
    public function setConfig($files=array())
    {
        // we are saving all paths in
        // the config directory of each application.
        foreach($files as $key=>$file){

            if(file_exists($file)){
                $this->register('appConfig',Str::lower($key),[
                    'namespace' =>app()->namespace()->config().'\\'.$key,
                    'file'      =>$file
                ]);
            }
        }
    }
}