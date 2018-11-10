<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Contracts\BootContracts;
use Resta\Event\EventManager as Event;

class EventDispatcher extends ApplicationProvider implements BootContracts {

    /**
     * @return mixed|void
     */
    public function boot(){

        // the eventDispatcher component provides tools
        // that allow your application components to communicate
        // with each other by dispatching events and listening to them.
        $this->app->bind('eventDispatcher',function(){
            return app()->namespace()->serviceEventDispatcher();
        },true);
    }

}