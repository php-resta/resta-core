<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\App;

class ApplicationInstanceLoader extends ApplicationProvider {

    public function boot(){

        //class alias for global app class
        class_alias(App::class,'application');
        $this->singleton()->appClass=new \application();
    }
}