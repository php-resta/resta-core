<?php

namespace Resta\EventDispatcher;

use Resta\Foundation\ApplicationProvider;

class EventDetached extends ApplicationProvider
{
    /**
     * EventDetached constructor.
     */
    public function __construct($app)
    {
        parent::__construct($app);

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

