<?php

namespace Resta\Foundation;

use Resta\App;
use Resta\Support\ClosureDispatcher;
use Resta\Exception\ErrorHandler;
use Resta\Support\ClassAliasGroup;
use Resta\Support\ReflectionProcess;
use Resta\GlobalLoaders\GlobalAccessor;
use Resta\Foundation\ApplicationProvider;
use Resta\Foundation\Bootstrapper\BootLoader;
use Resta\Container\ContainerInstanceResolver;

class ApplicationPreLoader extends ApplicationProvider
{
    /**
     * check store directory
     *
     * @return void|mixed
     */
    private function isAvailableStore()
    {
        // if the store directory is available,
        // then the application process continues.
        // if not available, only the core is executed.
        if(file_exists(app()->path()->storeDir())){
            $this->app->register('isAvailableStore',true);
        }
        else{
            $this->app->register('isAvailableStore',false);
        }
    }

    /**
     * @return void
     */
    public function handle()
    {
        //set base instances
        $this->setBaseInstances();

        //we define the general application instance object.
        define('appInstance',(base64_encode(serialize($this))));

        //main loader for application
        $this->mainLoader();

        //control of required store classes.
        $this->isAvailableStore();


        $this->app->bind('accessor',function(){
            return GlobalAccessor::class;
        });

        // sets a user-defined error handler function
        // this function can be used for defining your own way of handling errors during runtime,
        // for example in applications in which you need to do cleanup of data/files when a critical error happens,
        // or when you need to trigger an error under certain conditions (using trigger_error()).
        if(core()->isAvailableStore){
            $this->app->bind('exception',function(){
                return ErrorHandler::class;
            });
        }
    }

    /**
     * @return void
     */
    private function mainLoader()
    {
        //we can use this method to move an instance of the application class with the kernel object
        //and easily resolve an encrypted instance of all the kernel variables in our helper class.
        ClassAliasGroup::setAlias(App::class,'application');

        //For the application, we create the object that the register method,
        // which is the container center, is connected to by the kernel object register method.
        $this->app->register('container',$this->app);

        //get manifest bootManager.php
        $seperator = DIRECTORY_SEPARATOR;
        $bootManager = root.''.$seperator.'bootstrapper'.$seperator.'Manifest'.$seperator.'BootManager.php';

        // We add manifest configuration variables
        // to the manifest property in the kernel.
        if(file_exists($bootManager)){
            $this->app->register('manifest','bootManager',require($bootManager));
        }

        // We are saving the application class to
        // the container object for the appClass value.
        $this->app->register('appClass',new \application());

        //set closure bind instance for application
        $this->app->register('appClosureInstance',ClosureDispatcher::bind(app()));

        //set closure bind instance for bootLoader class
        $this->app->register('bootLoader',ClosureDispatcher::bind($this->app->makeBind(BootLoader::class)));

    }

    /**
     * @return void|mixed
     */
    private function setBaseInstances()
    {
        //register as instance application object
        // and container instance resolve
        $this->app->instance('app',$this->app);
        $this->app->instance('containerInstanceResolve',ContainerInstanceResolver::class);
        $this->app->instance('reflection',ReflectionProcess::class);
    }

}