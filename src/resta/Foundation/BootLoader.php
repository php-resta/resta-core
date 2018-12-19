<?php

namespace Resta\Foundation;

use Resta\Utils;
use Resta\ApplicationProvider;
use Resta\Logger\LoggerService;
use Resta\Exception\ErrorHandler;
use Resta\Contracts\BootContracts;
use Resta\Response\ResponseApplication;
use Resta\UrlParse\UrlParseApplication;
use Resta\Config\ConfigManager as Config;
use Resta\Contracts\ApplicationContracts;
use Resta\Middleware\ApplicationMiddleware;
use Resta\Console\Console as ConsoleManager;
use Resta\Routing\RouteApplication as Router;
use Resta\Environment\EnvironmentConfiguration;
use Resta\Encrypter\Encrypter as EncrypterProvider;
use Resta\GlobalLoaders\GlobalAccessor as Accessor;

class BootLoader extends ApplicationProvider implements BootContracts
{
    /**
     * @var $bootstrapper
     */
    public $bootstrapper;

    /**
     * @return mixed|void
     */
    private function accessor()
    {
        $this->app->bind('accessor',function(){
            return Accessor::class;
        });
    }

    /**
     * @return mixed|void
     */
    private function appConsole()
    {
        //if the console is true
        //console app runner
        if(Utils::isRequestConsole()){

            //If the second parameter is sent true to the application builder,
            //all operations are performed by the console and the custom booting are executed
            $this->app->bind('appConsole',ConsoleManager::class,true);
        }
    }

    /**
     * @return mixed|void
     */
    private function appProvider()
    {
        // your app provider will include identifiers
        // that can be bound for each group of your project.
        $this->app->bind('appProvider',function(){
            return app()->namespace()->kernel().'\AppProvider';
        });
    }

    /**
     * @return mixed|void
     */
    public function boot()
    {
        $this->{$this->bootstrapper}();
    }

    /**
     * @return mixed|void
     */
    private function configManager()
    {
        // this is your application's config installer.
        // you can easily access the config variables with the config installer.
        $this->app->bind('config',function(){
            return Config::class;
        },true);
    }

    /**
     * @return mixed|void
     */
    private function serviceContainer()
    {
        // with url parsing,the application route for
        // the rest project is determined after the route variables from the URL are assigned to the kernel url object.
        $this->app->bind('container',function(){
            return app()->namespace()->serviceContainer();
        },true);
    }

    /**
     * @return mixed|void
     */
    private function encrypter()
    {
        // the rest system will assign a random key to your application for you.
        // this application will single the advantages of using the rest system for your application in particular.
        $this->app->bind('encrypter',function(){
            return EncrypterProvider::class;
        });
    }

    /**
     * @return mixed|void
     */
    private function environment()
    {
        // it is often helpful to have different configuration values based on
        // the environment where the application is running.for example,
        // you may wish to use a different cache driver locally than you do on your production server.
        $this->app->bind('environment',function(){
            return EnvironmentConfiguration::class;
        },true);
    }

    /**
     * @return mixed|void
     */
    private function eventDispatcher()
    {
        // the eventDispatcher component provides tools
        // that allow your application components to communicate
        // with each other by dispatching events and listening to them.
        $this->app->bind('eventDispatcher',function(){
            return app()->namespace()->serviceEventDispatcher();
        },true);
    }

    /**
     * @return mixed|void
     */
    private function exception()
    {
        // sets a user-defined error handler function
        // this function can be used for defining your own way of handling errors during runtime,
        // for example in applications in which you need to do cleanup of data/files when a critical error happens,
        // or when you need to trigger an error under certain conditions (using trigger_error()).
        $this->app->bind('exception',function(){
            return ErrorHandler::class;
        });
    }

    /**
     * @return mixed|void
     */
    private function logger()
    {
        // to help you learn more about what's happening within your application,
        // rest system provides robust logging services that allow you to log messages to files,
        // the system error log, and even to Slack to notify your entire team.
        $this->app->bind('logger',function(){
            return LoggerService::class;
        },true);
    }

    /**
     * @return mixed|void
     */
    private function middleware()
    {
        // when your application is requested, the middleware classes are running before all bootstrapper executables.
        // thus, if you make http request your application, you can verify with an intermediate middleware layer
        // and throw an exception.
        $this->app->bind('middleware',function(){
            return ApplicationMiddleware::class;
        });
    }

    /**
     * @return mixed|void
     */
    private function responseManager()
    {
        // we determine kind of output with the response manager
        // json as default or [xml,wsdl]
        $this->app->bind('response',function(){
            return ResponseApplication::class;
        });
    }

    /**
     * @return mixed|void
     */
    private function router()
    {
        // route operations are the last part of the system run. In this section,
        // a route operation is passed through the url process and output is sent to the screen according to
        // the method file to be called by the application
        $this->app->bind('router',function(){
            return Router::class;
        });
    }

    /**
     * @return mixed|void
     */
    private function urlProvider()
    {
        // with url parsing,the application route for
        // the rest project is determined after the route variables from the URL are assigned to the kernel url object.
        $this->app->bind('url',function(){
            return UrlParseApplication::class;
        });
    }
}