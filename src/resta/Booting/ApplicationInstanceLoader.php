<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\App;
use Resta\Container;
use Resta\Traits\InstanceRegister;

class ApplicationInstanceLoader extends ApplicationProvider {

    //Instance register
    use InstanceRegister;

    /**
     * @method boot
     * @return void
     */
    public function boot(){

        //we can use this method to move an instance of the application class with the kernel object
        //and easily resolve an encrypted instance of all the kernel variables in our helper class.
        class_alias(App::class,'application');
        $this->register('appClass',new \application());

        //we register the general application instance object.
        define('appInstance',(base64_encode(serialize($this))));


    }
}