<?php

namespace Resta\EventDispatcher;

use Resta\Foundation\ApplicationProvider;
use Resta\Support\Utils;

class EventDetached extends ApplicationProvider
{
    /**
     * EventDetached constructor.
     */
    public function __construct($app)
    {
        parent::__construct($app);
    }

    /**
     * handler dispatch
     *
     * @param $event
     * @param $events
     */
    protected function handlerDispatch($event,$events)
    {
        $eventName = lcfirst(class_basename($event));

        if(isset($events[$eventName])){

            $listenerPath = app()->namespace()->optionalListeners();

            foreach($events[$eventName] as $listeners){

                $listenerClass = $listenerPath.'\\'.ucfirst($listeners);

                if(Utils::isNamespaceExists($listenerClass)){
                    $this->app->resolve($listenerClass)->handle($event);
                }
            }
        }
    }

    /**
     * handler dispatch for string
     *
     * @param $event
     * @param $eventName
     * @return null
     */
    protected function handlerDispatchForString($event,$eventName)
    {
        $listeners = $this->getListeners();

        if(isset($listeners[$event][$eventName])){
            return $listeners[$event][$eventName];
        }

        return null;
    }
}

