<?php

namespace Resta\Foundation;

use Resta\Foundation\Bootstrapper\BootLoader;
use Resta\Support\Str;
use Resta\Config\Config;
use DI\NotFoundException;
use Resta\Support\Command;
use DI\DependencyException;
use Resta\Traits\ApplicationPath;
use Illuminate\Support\Collection;
use Resta\Contracts\ApplicationContracts;
use Resta\Environment\EnvironmentProvider;
use Resta\Contracts\ConfigProviderContracts;
use Resta\Contracts\ClosureDispatcherContracts;
use Resta\Foundation\Bootstrapper\Bootstrappers;

class Application extends Kernel implements ApplicationContracts
{
    //get app paths
    use ApplicationPath;

    /**
     * The Resta api designer version.
     *
     * @var string
     */
    protected const VERSION = '1.0.0';

    /**
     * @var bool
     */
    protected $console;

    /**
     * Application constructor.
     *
     * @param bool $console
     */
    public function __construct($console=false)
    {
        // get console status for cli
        $this->console = $console;

        // the bootstrapper method is the initial process
        // that runs the individual methods that the application initiated.
        new Bootstrappers($this);
    }

    /**
     * check if the object in binding is available
     *
     * @param string $object
     * @return bool
     */
    public function checkBindings($object) : bool
    {
        // the booted objects are saved to the kernel.
        // this method checks whether these objects are registered.
        return (isset($this['bindings'],$this['bindings'][$object]));
    }

    /**
     * handle application command
     *
     * @param string $command
     * @param array $arguments
     * @return mixed
     *
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function command($command, $arguments = array())
    {
        // the Process class executes a command in a sub-process,
        // taking care of the differences between operating system
        // and escaping arguments to prevent security issues.
        // It replaces PHP functions like exec, passthru, shell_exec and system
        return $this->resolve(Command::class,['command'=>$command,'args'=>$arguments])->handle();
    }

    /**
     * get kernel command list
     *
     * @return array
     */
    public function commandList() : array
    {
        //get command list from kernel
        return $this->commandList;
    }

    /**
     * get configuration values
     *
     * @param string $config
     * @return mixed
     *
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function config($config)
    {
        if($this->checkBindings(__FUNCTION__)){
            return Config::make($config)->get();
        }

        // if the environment is not booted,
        // it creates a direct missing ring
        // and we have to reinstall the environment to remove it.
        $this->loadIfNotExistBoot([__FUNCTION__]);
        return $this->config($config);
    }

    /**
     * console kernel object
     *
     * @return bool
     */
    public function console() : bool
    {
        // controlling the console object is
        // intended to make sure that the kernel bootstrap classes do not work.
        return $this->console;
    }

    /**
     * get core path
     *
     * @return mixed
     */
    public function corePath()
    {
        // get the directory
        // where kernel files are running to the kernel object.
        return core()->corePath ?: null;
    }

    /**
     * get environment variables
     *
     * @param array $environment
     * @return mixed|string
     */
    public function environment($environment=array())
    {
        if($this->checkBindings(__FUNCTION__)){

            $arguments = (isset(func_get_args()[0]))
                ? func_get_args()[0] : func_get_args();

            /** @var EnvironmentProvider $environmentContainer */
            $environmentContainer = $this['environment'];

            return $environmentContainer->environment(
                $arguments,$this['environmentVariables']
            );
        }

        // if the environment is not booted,
        // it creates a direct missing ring
        // and we have to reinstall the environment to remove it.
        $this->loadIfNotExistBoot([__FUNCTION__]);
        return $this->environment();
    }

    /**
     * handle application
     *
     * @return mixed
     */
    public function handle()
    {
        // this is the main calling place of your application.
        // if you come via http, the kernel response value is appraised.
        // if you come via console, the kernel console value is appraised.
        return ($this->console()) ? null : $this['result'];
    }

    /**
     * Determine if application locale is the given locale.
     *
     * @return bool
     */
    public function isLocale() : bool
    {
        //check environment for local
        return $this->environment() === 'local';
    }

    /**
     * get all kernel bootstrapper groups keys
     *
     * @return array
     */
    public function kernelGroupKeys() : array
    {
        $properties = [];

        // with the help of reflection instance,
        // we get the kernel properties extended with the application object.
        foreach ($this['reflection']($this)->getProperties() as $property){
            $properties[] = $property->getName();
        }

        // we get the names of
        // the kernel properties ended with groups through the Collection class.
        [$groups] = Collection::make($properties)->partition(function($properties){
            return Str::endsWith($properties,'Groups');
        });

        //as a result, kernel groups are being returned.
        return array_values($groups->all());
    }

    /**
     * kernel groups name lists
     *
     * @return array
     */
    public function kernelGroupList() : array
    {
        $list = [];

        //get kernel group names with manifest method
        foreach ($this->kernelGroupKeys() as $groupKey){
            $list = array_merge($list,$this->manifest($groupKey));
        }

        return $list;
    }

    /**
     * customer configuration loader for core
     *
     * @param callable $callback
     * @return mixed
     */
    public function loadConfig(callable $callback)
    {
        // it adds the values in path data specified
        // by callback to the configuration values.
        if($this['config'] instanceof ConfigProviderContracts){

            //set your path for config loader
            return tap($this['config'],function(ConfigProviderContracts $config) use($callback) {
                return $config->setConfig(call_user_func($callback));
            });
        }

        //set config instance exception for application
        exception()->unexpectedValue('config instance is not loaded for application container');
    }

    /**
     * load if not exist boot
     *
     * @param array $loaders
     * @return mixed|void
     */
    public function loadIfNotExistBoot($loaders=array())
    {
        //get kernel group list from application
        $kernelGroupList = $this->kernelGroupList();

        /** @var ClosureDispatcherContracts $closureBootLoader */
        $closureBootLoader = $this['closureBootLoader'];

        foreach ($loaders as $loader){

            // if a service needs another boot service,
            // the service is directly installed here and the service needs are resolved.
            if(isset($kernelGroupList[$loader]) && $this->checkBindings($loader)===false){

                //with the boot loader kernel,we get the boot loader method.
                $closureBootLoader->call(function() use($loader,$kernelGroupList) {

                    /** @var BootLoader $bootLoader */
                    $bootLoader = $this;
                    $bootLoader->setBootstrapper($kernelGroupList[$loader]);
                    return $bootLoader->boot();
                });
            }
        }
    }

    /**
     * get kernel maker from manifest
     *
     * @param string $maker
     * @return mixed
     */
    public function manifest($maker)
    {
        /** @var Bootstrappers $bootstrapper */
        $bootstrapper = $this['bootstrapper'];

        //kernel manifest bootstrapper
        return $bootstrapper->bootFire(null,$maker);
    }

    /**
     * check if the request is console
     *
     * @return bool
     */
    public function runningInConsole() : bool
    {
        //Determine if the application is running in the console.
        return php_sapi_name() === 'cli' || php_sapi_name() === 'phpdbg';
    }

    /**
     * get service providers
     *
     * @return array
     */
    public function serviceProviders() : array
    {
        //get project providers from config kernel
        $providers = (is_array(config('kernel.providers')))
            ? config('kernel.providers')
            : [];

        //core kernel providers and project providers have been merged
        return array_merge($this->manifest('providers'),$providers);
    }

    /**
     * application structure directory paths changing
     *
     * @param string $name
     * @param string $path
     * @return mixed|void
     */
    public function setPaths($name,$path)
    {
        // save the globally identified paths to
        // the global container object of the resta.
        if(file_exists($path)){
            $this->register('paths',$name,$path);
        }
    }

    /**
     * Get the version number of the application.
     *
     * @return mixed|string
     */
    public function version()
    {
        //get resta application version number
        return static::VERSION;
    }
}