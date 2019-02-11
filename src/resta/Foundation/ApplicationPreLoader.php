<?php

namespace Resta\Foundation;

use Resta\App;
use Resta\ClassAliasGroup;
use Resta\ClosureDispatcher;
use Resta\ApplicationProvider;
use Resta\Exception\ErrorHandler;
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
            require($bootManager);
            $this->app->register('manifest','bootManager',$bootManager);
        }

        // We are saving the application class to
        // the container object for the appClass value.
        $this->app->register('appClass',new \application());

        //set closure bind instance for application
        $this->app->register('appClosureInstance',ClosureDispatcher::bind(app()));

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
    }

}