<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Contracts\BootContracts;

class AppProvider extends ApplicationProvider implements BootContracts
{
    /**
     * @return mixed|void
     */
    public function boot()
    {
        // your app provider will include identifiers
        // that can be bound for each group of your project.
        $this->app->bind('appProvider',function(){
            return app()->namespace()->kernel().'\AppProvider';
        });
    }
}