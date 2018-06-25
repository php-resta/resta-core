<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Event\EventManager as Event;

class EventDispatcher extends ApplicationProvider {

    /**
     * @return void
     */
    public function boot(){

        //This is your application's config installer.
        //You can easily access the config variables with the config installer.
        $this->app->bind('event-dispatcher',function(){
            return app()->namespace()->serviceEventDispatcher();
        },true);
    }

}