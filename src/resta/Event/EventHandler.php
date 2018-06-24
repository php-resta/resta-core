<?php

namespace Resta\Event;

use Resta\Utils;

class EventHandler {

    /**
     * @param $event
     */
    public function dispatcher($event){

        $eventName=lcfirst(class_basename($event));

        if(isset($this->listen[$eventName])){

            foreach ($this->listen[$eventName] as $listen){

                $listenNamespace=app()->namespace()->optionalListeners().'\\'.ucfirst($listen);
                
                Utils::callBind([$listenNamespace,'handle'],[ucfirst($eventName)=>$event]);
            }
        }
    }

}

