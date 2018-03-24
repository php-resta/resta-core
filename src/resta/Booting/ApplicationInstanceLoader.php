<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\App;

class ApplicationInstanceLoader extends ApplicationProvider {

    /**
     * @method boot
     * @return void
     */
    public function boot(){

        //we can use this method to move an instance of the application class with the kernel object
        //and easily resolve an encrypted instance of all the kernel variables in our helper class.
        class_alias(App::class,'application');
        $this->singleton()->appClass=new \application();
    }
}