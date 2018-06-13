<?php

namespace Resta\Config;

use Resta\Utils;
use Resta\StaticPathModel;
use Resta\ApplicationProvider;

class ConfigLoader extends ApplicationProvider {

    /**
     * @method handle
     * @return mixed
     */
    public function handle(){

        //We run a glob function for all of the config files,
        //where we pass namespace and paths to a kernel object and process them.
        $configFiles=Utils::glob(app()->path()->config());

        //The config object is a kernel object
        //that can be used to call all class and array files in the config directory of the project.
        $this->singleton()->configGlobalInstance->setConfig($configFiles);
    }

}