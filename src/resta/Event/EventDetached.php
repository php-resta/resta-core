<?php

namespace Resta\Event;

class EventDetached
{
    /**
     * EventDetached constructor.
     */
    public function __construct()
    {
        // you need to get the instance of
        // the serviceDispatcherController class in your application to run the event dispatcher.
        // in this case you will absolutely have to use the event helper method. If not, then accessing
        // this class directly will result in an exception.
        $this->checkEventDetached();
    }

    /**
     * @return void
     */
    public function checkEventDetached()
    {
        //checker assignerDispatches for event dispatcher object
        if(!method_exists($this,'assignerDispatches')){
            exception()->badMethodCall('detached event-dispatcher running is false');
        }
    }
}

