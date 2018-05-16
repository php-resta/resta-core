<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\App;
use Resta\Container;
use Resta\Foundation\RegisterAppBound;

class ApplicationInstanceLoader extends ApplicationProvider {

    /**
     * @method boot
     * @return void
     */
    public function boot(){

        //we can use this method to move an instance of the application class with the kernel object
        //and easily resolve an encrypted instance of all the kernel variables in our helper class.
        class_alias(App::class,'application');

        //For the application, we create the object that the register method,
        // which is the container center, is connected to by the kernel objesine register method.
        $registerAppBound=$this->makeBind(RegisterAppBound::class);
        $registerAppBound->register('bound',$registerAppBound);
        $this->register('appClass',new \application());

        //we register the general application instance object.
        define('appInstance',(base64_encode(serialize($this))));


    }
}