<?php

namespace Resta\GlobalLoaders;

use Resta\Support\Str;
use Resta\Support\Utils;
use Resta\StaticPathList;
use Resta\ApplicationProvider;

class Config extends ApplicationProvider
{
    /**
     * @param array $files
     * @return void
     */
    public function setConfig($files=array())
    {
        // we are adding kernel variables
        $files['Kernel']        = path()->kernel().''.DIRECTORY_SEPARATOR.''.StaticPathList::$kernel.'.php';
        $files['Constructor']   = path()->storeConfigDir().''.DIRECTORY_SEPARATOR.'AppConstructor.php';

        // we are saving all paths in
        // the config directory of each application.
        foreach($files as $key=>$file){

            if(file_exists($file)){
                $this->register('appConfig',Str::lower($key),[
                    'namespace' =>Utils::getNamespace($file),
                    'file'      =>$file
                ]);
            }
        }
    }
}