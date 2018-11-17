<?php

namespace Resta\Environment;

use Resta\GlobalLoaders\Environment as EnvironmentGlobalInstance;

class EnvironmentConfiguration {

    /**
     * @param array $var
     * @param $environment null
     * @return mixed
     */
    public static function environment($var=array(),$environment=null){

        //environment is recognized as a production environment directly
        //if there is no env object in the environment variable.
        $isProduction=(isset($environment['env'])) ? $environment['env'] : 'production';

        //we issue a controlled environment key map for the submitted environment
        return (count($var)===0) ? $isProduction : self::getEnvironmentForVariables($var,$environment);
    }

    /**
     * @param array $var
     * @param null $environment
     * @return string
     */
    private static function getEnvironmentForVariables($var=array(),$environment=null){

        //environment variable specified by the variable is checked in the defined file
        //and the value is returned accordingly.
        if(isset($environment[$var[0]])){
            return $environment[$var[0]];
        }
        return $var[1];
    }

    /**
     * @param EnvironmentGlobalInstance $environment
     */
    public function handle(EnvironmentGlobalInstance $environment)
    {
        //set define for config
        define ('environment',true);

        //where we do the checks for the environment file type,
        //and if no configuration file is found, the system throws an exception.
        $configuration=app()->makeBind(CheckEnvironmentFile::class)->checkConfiguration();

        //We are globalizing environment variables.
        $environment->set($configuration);
    }
}