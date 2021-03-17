<?php

namespace Resta\Environment;

use Resta\Contracts\HandleContracts;
use Resta\Foundation\ApplicationProvider;

class EnvironmentProvider extends ApplicationProvider implements HandleContracts
{
    /**
     * get environtment
     *
     * @param array $var
     * @param null $environment
     * @return string
     */
    public function environment($var=array(),$environment=null)
    {
        //The first variable is built on production.
        $env = 'production';

        //environment is recognized as a production environment directly
        //if there is no env object in the environment variable.
        if(isset($environment['env'])){
            $env = $environment['env'];
        }

        //we issue a controlled environment key map for the submitted environment
        return (count($var)===0) ? $env : self::getEnvironmentForVariables($var,$environment);
    }

    /**
     * get environment for variables
     *
     * @param array $var
     * @param null $environment
     * @return mixed
     */
    private static function getEnvironmentForVariables($var=array(),$environment=null)
    {
        //environment variable specified by the variable is checked in the defined file
        //and the value is returned accordingly.
        return $environment[$var[0]] ?? $var[1];
    }

    /**
     * environment provider handle
     *
     * @return void
     */
    public function handle()
    {
        //set define for config
        define ('environment',true);

        //where we do the checks for the environment file type,
        //and if no configuration file is found, the system throws an exception.
        $configuration = $this->app->resolve(CheckEnvironmentFile::class)->checkConfiguration();

        //We are globalizing environment variables.
        $this->set($configuration);
    }

    /**
     * register to container for environment
     *
     * @param null $configuration
     * @return void
     */
    private function set($configuration=null)
    {
        //we are doing global registration for env and var value.
        $this->app->register('environmentVariables',$configuration);
        $this->app->register('env',$this->environment([],$this->app['environmentVariables']));
    }
}