<?php

namespace Resta\EventDispatcher;

use Resta\Foundation\ApplicationProvider;

class EventDispatcherKernelAssigner extends ApplicationProvider
{
    /**
     * @param $dispatcher
     * @return void
     */
    public function setEventDispatcher($dispatcher)
    {
        //we save to kernel object value of the event-dispatcher
        $this->app->register('events',$dispatcher);
    }
}