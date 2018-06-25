<?php

namespace Resta\Event;

use Resta\Utils;

class EventHandler {

    /**
     * @param $event
     */
    public function dispatcher($event){

        //get event name by class_basename
        $eventName=lcfirst(class_basename($event));

        $listeners=$this->getListeners();

        //check event name on the event listen
        if(isset($listeners[$eventName])){

            // we loop through all the listen objects and call the handle method.
            // so that all events will be processed through the event auxiliary method.
            foreach ($listeners[$eventName] as $key=>$listen){

                if($key==="subscriber"){

                    //If subscriber method exists; in this case we are running a subscribe event.
                    $subscriberCallMethod=$listeners[$eventName]['subscriber'][$event->param];
                    $event->$subscriberCallMethod();
                }
                else{

                    // the listening object will be resolved to the namespace value
                    // in the listeners array and then the service container method via dependency injection.
                    $listenNamespace=app()->namespace()->optionalListeners().'\\'.ucfirst($listen);
                    Utils::callBind([$listenNamespace,'handle'],[ucfirst($eventName)=>$event]);
                }

            }
        }
    }

    /**
     * @param array $listener
     */
    public function addListener($listener=array()){

        // To take advantage of an existing event,
        // you need to connect a listener to the dispatcher
        // so that it can be notified when the event is dispatched.
        // A call to the dispatcher's addListener() method associates any valid PHP callable to an event:
        $this->listen=array_merge($this->getListeners(),$listener);
    }

    /**
     * @param array $listener
     */
    public function addSubscriber($listener=array()){

        // To take advantage of an existing event,
        // you need to connect a listener to the dispatcher
        // so that it can be notified when the event is dispatched.
        // A call to the dispatcher's addListener() method associates any valid PHP callable to an event:
        $this->listen=array_merge($this->getListeners(),[$this->event=>['subscriber'=>$listener]]);
    }

    /**
     * @return mixed
     */
    public function getListeners(){

        if(isset(app()->singleton()->events)) {
            return app()->singleton()->events;
        }
        return $this->listen;
    }

}

