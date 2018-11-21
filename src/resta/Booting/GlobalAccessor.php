<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Contracts\BootContracts;
use Resta\GlobalLoaders\GlobalAccessor as Accessor;

class GlobalAccessor extends ApplicationProvider implements BootContracts
{
    /**
     * @return mixed|void
     */
    public function boot()
    {
        $this->app->bind('accessor',function(){
            return Accessor::class;
        });
    }
}