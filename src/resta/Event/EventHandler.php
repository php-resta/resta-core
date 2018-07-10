<?php

namespace Resta\Event;

use Resta\Utils;
use Resta\Contracts\EventDispatcherContracts;

class EventHandler extends EventDetached implements EventDispatcherContracts {

    /**
     * @var array $dispatches
     */
    protected $dispatches=[];

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
     * @param array $subscriber
     */
    public function addSubscriber($subscriber=array()){

        // To take advantage of an existing event,
        // you need to connect a listener to the dispatcher
        // so that it can be notified when the event is dispatched.
        // A call to the dispatcher's addListener() method associates any valid PHP callable to an event:
        $this->listen=array_merge($this->getListeners(),[$this->event=>['subscriber'=>$subscriber]]);
    }

    /**
     * @param $event
     * @param null $callable
     */
    public function dispatcher($event,$callable=null){

        // we will assign global dispatcher variables so that
        // we can see these values will make it easier for us to readability.
        $this->assignerDispatches($event,$callable);

        //get event listeners
        $listeners=$this->getListeners();

        //check event name on the event listen
        if(isset($listeners[$this->dispatches['eventName']])){

            // we loop through all the listen objects and call the handle method.
            // so that all events will be processed through the event auxiliary method.
            foreach ($listeners[$this->dispatches['eventName']] as $key=>$listen){

                // if the key variable contains a subscriber string,
                // then we run the subscriber process.
                $this->eventSubscriberProcess($key,$listeners,function() use($listen) {

                    // the listening object will be resolved to the namespace value
                    // in the listeners array and then the service container method via dependency injection.
                    $this->eventListen($listen);
                });
            }
        }
    }
}

