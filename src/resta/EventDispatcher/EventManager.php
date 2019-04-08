<?php

namespace Resta\EventDispatcher;

class EventManager extends EventHandler
{
    /**
     * get listeners for event dispathcer
     *
     * @return mixed
     */
    public function getListeners()
    {
        if(isset($this->app['events'])){
            return $this->app['events'];
        }
        return $this->listen;
    }

    /**
     * dispatch event
     *
     * @param $event
     * @return mixed|void
     */
    public function dispatch($event,$eventName='default')
    {
        //recorded events must be.
        if(isset($this->app['events'])){

            // in the logic of the events,
            // the default keyi mandatory.
            $events = $this->app['events']['events'] ?? ['default'=>[]];

            if(is_object($event)){
                return $this->handlerDispatch($event,$events[$eventName]);
            }
        }

        return $this->handlerDispatchForString($event,$eventName);
    }

    /**
     * event provider application handle
     *
     * @return void
     */
    public function handle()
    {
        //set constant event-dispatcher
        define('event-dispatcher',true);

        //we save to kernel object value of the event-dispatcher
        $this->addListener('events','default',$this->getListeners());
    }
}

