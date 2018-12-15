<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;

class EventDispatcher extends ApplicationProvider
{
    /**
     * @param $dispatcher
     * @return void
     */
    public function setEventDispatcher($dispatcher)
    {
        //we save to kernel object value of the event-dispatcher
        $this->register('events',$dispatcher);
    }
}