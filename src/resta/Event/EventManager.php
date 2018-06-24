<?php

namespace Resta\Event;

use Resta\Contracts\HandleContracts;

class EventManager extends EventHandler implements HandleContracts {

    /**
     * @return mixed|void
     */
    public function handle(){

        //we get event-dispatcher list
        $eventDispatchers=app()->singleton()->bindings['event-dispatcher']->listen;

        //we save to kernel object value of the event-dispatcher
        appInstance()->register('events',$eventDispatchers);

    }
}

