<?php

namespace Resta\Foundation\Bootstrapper;

use Resta\Support\Utils;
use Resta\Url\UrlProvider;
use Resta\Router\RouteProvider;
use Resta\Logger\LoggerProvider;
use Resta\Config\ConfigProvider;
use Resta\Exception\ErrorHandler;
use Resta\Contracts\BootContracts;
use Resta\Provider\ServiceProvider;
use Resta\Response\ResponseProvider;
use Resta\Middleware\MiddlewareProvider;
use Resta\Foundation\ApplicationProvider;
use Resta\Contracts\ApplicationContracts;
use Resta\Environment\EnvironmentProvider;
use Resta\Console\Console as ConsoleManager;
use Resta\Encrypter\EncrypterProvider as EncrypterProvider;

class BootLoader extends ApplicationProvider implements BootContracts
{
    /**
     * @var $bootstrapper
     */
    private $bootstrapper;

    /**
     * app console method
     *
     * @return mixed|void
     */
    private function appConsole()
    {
        //if the console is true
        //console app runner
        if($this->app->runningInConsole()
            && $this->app['isAvailableStore'] && $this->app->checkBindings('appConsole')===false){

            //If the second parameter is sent true to the application builder,
            //all operations are performed by the console and the custom booting are executed
            $this->app->make('appConsole',ConsoleManager::class,true);
        }
    }

    /**
     * default distributor boot method
     *
     * @return mixed|void
     */
    public function boot()
    {
        $this->{$this->bootstrapper}();
    }

    /**
     * config provider boot
     *
     * @return mixed|void
     */
    private function configProvider()
    {
        // this is your application's config installer.
        // you can easily access the config variables with the config installer.
        if($this->app->checkBindings('config')===false){
            $this->app->share('config',function($app){
                return $app['revision']['configProvider'] ?? ConfigProvider::class;
            });
        }
    }

    /**
     * encrypter boot
     *
     * @return mixed|void
     */
    private function encrypter()
    {
        // the rest system will assign a random key to your application for you.
        // this application will single the advantages of using the rest system for your application in particular.
        if(core()->isAvailableStore && $this->app->checkBindings('encrypter')===false){
            $this->app->share('encrypter',function($app){
                return $app['revision']['encrypter'] ?? EncrypterProvider::class;
            });
        }
    }

    /**
     * environment boot
     *
     * @return mixed|void
     */
    private function environment()
    {
        // it is often helpful to have different configuration values based onUrlParseApplication
        // the environment where the application is running.for example,
        // you may wish to use a different cache driver locally than you do on your production server.
        if($this->app->checkBindings('environment')===false){
            $this->app->share('environment',function($app){
                return $app['revision']['environment'] ?? EnvironmentProvider::class;
            });
        }
    }

    /**
     * event dispatcher boot
     *
     * @return mixed|void
     */
    private function eventDispatcher()
    {
        // the eventDispatcher component provides tools
        // that allow your application components to communicate
        // with each other by dispatching events and listening to them.
        if($this->app->checkBindings('eventDispatcher')===false){
            $this->app->share('eventDispatcher',function($app){
                if(Utils::isNamespaceExists(app()->namespace()->serviceEventDispatcher())){
                    return $app['revision']['eventDispatcher'] ?? app()->namespace()->serviceEventDispatcher();
                }
            });
        }

    }

    /**
     * logger boot
     *
     * @return mixed|void
     */
    private function logger()
    {
        // to help you learn more about what's happening within your application,
        // rest system provides robust logging services that allow you to log messages to files,
        // the system error log, and even to Slack to notify your entire team.
        if($this->app->checkBindings('logger')===false){
            $this->app->share('logger',function($app){
                return $app['revision']['logger'] ?? LoggerProvider::class;
            });
        }

    }

    /**
     * middleware boot
     *
     * @return mixed|void
     */
    private function middleware()
    {
        // when your application is requested, the middleware classes are running before all bootstrapper executables.
        // thus, if you make http request your application, you can verify with an intermediate middleware layer
        // and throw an exception.
        if(core()->isAvailableStore && $this->app->checkBindings('middleware')===false){
            $this->app->make('middleware',function($app){
                return $app['revision']['middleware'] ?? MiddlewareProvider::class;
            });
        }
    }

    /**
     * response manager boot
     *
     * @return mixed|void
     */
    private function responseManager()
    {
        // we determine kind of output with the response manager
        // json as default or [xml,wsdl]
        if($this->app->checkBindings('response')===false){
            $this->app->make('response',function($app){
                return $app['revision']['responseManager'] ?? ResponseProvider::class;
            });
        }
    }

    /**
     * router boot
     *
     * @return mixed|void
     */
    private function router()
    {
        // route operations are the last part of the system run. In this section,
        // a route operation is passed through the url process and output is sent to the screen according to
        // the method file to be called by the application
        if(core()->isAvailableStore && $this->app->checkBindings('router')===false){
            $this->app->make('router',function($app){
                return $app['revision']['router'] ?? RouteProvider::class;
            });
        }
    }

    /**
     * service provider boot
     *
     * @return mixed|void
     */
    private function serviceProvider()
    {
        if($this->app->checkBindings('serviceProvider')===false){
            $this->app->share('serviceProvider',function($app){
                return $app['revision']['serviceProvider'] ?? ServiceProvider::class;
            });
        }
    }

    /**
     * set bootstrapper property
     *
     * @param null $bootstrapper
     */
    public function setBootstrapper($bootstrapper=null)
    {
        $this->bootstrapper = $bootstrapper;
    }

    /**
     * url provider boot
     *
     * @return mixed|void
     */
    private function urlProvider()
    {
        // with url parsing,the application route for
        // the rest project is determined after the route variables from the URL are assigned to the kernel url object.
        if(core()->isAvailableStore && $this->app->checkBindings('url')===false){
            $this->app->make('url',function($app){
                return $app['revision']['urlProvider'] ?? UrlProvider::class;
            });
        }
    }

    /**
     * special revision boot
     *
     * @param $name
     * @param $arguments
     */
    public function __call($name,$arguments)
    {
        // we use the methodological context
        // for kernel group values that are replaced with revision.
       $revisionBoot = array_search($name,app()['revision']);
       if(method_exists($this,$revisionBoot)){
           return $this->{$revisionBoot}();
       }

       exception()->badFunctionCall('There is no boot method named '.$name);
    }
}