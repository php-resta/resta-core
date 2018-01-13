<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\GlobalLoaders\GlobalAccessor as Accessor;

class GlobalAccessor extends ApplicationProvider {

    /**
     * @method boot
     * @return void
     */
    public function boot(){

        $this->app->bind('accessor',function(){
            return Accessor::class;
        });
    }

}