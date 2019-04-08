<?php

namespace Resta\EventDispatcher;

use Resta\Contracts\EventDispatcherContracts;

class EventHandler extends EventDetached implements EventDispatcherContracts
{
    /**
     * @var $event
     */
    private $events;

    /**
     * set event for application
     *
     * @param $eventName
     * @param $abstract
     * @param $concrete
     * @return mixed|void
     */
    public function addListener($eventName, $abstract,$concrete)
    {
        if(!isset($this->events[$eventName][strtolower($abstract)])){

            $this->events[$eventName][strtolower($abstract)] = $concrete;

            //we save to kernel object value of the event-dispatcher
            $this->app->register('events',$eventName,$this->events[$eventName]);
        }
    }

    /**
     * @param array $subscriber
     * @return mixed|void
     */
    public function addSubscriber($subscriber=array())
    {
        // To take advantage of an existing event,
        // you need to connect a listener to the dispatcher
        // so that it can be notified when the event is dispatched.
        // A call to the dispatcher's addListener() method associates any valid PHP callable to an event:
        $this->listen=array_merge($this->getListeners(),[$this->event=>['subscriber'=>$subscriber]]);
    }
}

