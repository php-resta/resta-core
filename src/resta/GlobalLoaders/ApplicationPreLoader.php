<?php

namespace Resta\GlobalLoaders;

use Resta\App;
use Resta\ClassAliasGroup;
use Resta\ApplicationProvider;
use Resta\Foundation\RegisterAppBound;

class ApplicationPreLoader extends ApplicationProvider  {

    /**
     * register pre loader variables to kernel
     * @return void|mixed
     */
    public function handle(){

        //we define the general application instance object.
        define('appInstance',(base64_encode(serialize($this))));

        //main loader for application
        $this->mainLoader();
    }

    /**
     * @return void|mixed
     */
    private function mainLoader(){

        //we can use this method to move an instance of the application class with the kernel object
        //and easily resolve an encrypted instance of all the kernel variables in our helper class.
        ClassAliasGroup::setAlias(App::class,'application');

        //For the application, we create the object that the register method,
        // which is the container center, is connected to by the kernel objesine register method.
        $registerAppBound=$this->app->makeBind(RegisterAppBound::class);
        $registerAppBound->register('bound',$registerAppBound);

        //We add manifest configuration variables to the manifest property in the kernel.
        $bootManager=require(root.'/bootstrapper/Manifest/BootManager.php');
        $registerAppBound->register('manifest','bootManager',$bootManager);

        // We are saving the application class to
        // the container object for the appClass value.
        $this->app->kernel()->bound->register('appClass',new \application());
    }

}