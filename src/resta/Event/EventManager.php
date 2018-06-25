<?php

namespace Resta\Event;

use Resta\Utils;
use Resta\Contracts\HandleContracts;

class EventManager extends EventHandler implements HandleContracts {

    /**
     * @var $event
     */
    protected $event;

    /**
     * @return mixed|void
     */
    public function handle(){

        //get subscribe event list with event subscribe handler
        $dispatcherList=$this->eventSubscribeHandler($this);

        //we save to kernel object value of the event-dispatcher
        appInstance()->register('events',$dispatcherList);

    }

    /**
     * @param $dispatcher
     */
    private function eventSubscribeHandler($dispatcher){

        //get subscriber directory namespace
        $subscriberDirectory=app()->namespace()->optionalSubscribers();

        foreach ($dispatcher->subscribe as $subscribe){

            //set event object for subscribe
            $this->event=$subscribe;

            //get subscriber namespace
            $subscriberNamespace=$subscriberDirectory.'\\'.$this->event;

            if(Utils::isNamespaceExists($subscriberNamespace)){

                $subscriberInstance=app()->makeBind($subscriberNamespace,['param'=>null]);
                $subscriberInstance->subscriber($this);
            }
        }

        //get listeners
        return $this->getListeners();
    }
}

