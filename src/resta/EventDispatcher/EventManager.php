<?php

namespace Resta\EventDispatcher;

use Resta\Support\Utils;
use Resta\Container\DIContainerManager;

class EventManager extends EventHandler
{
    /**,
     * @var $event
     */
    protected $event;

    /**
     * @param $event
     * @param $callable
     */
    protected function assignerDispatches($event,$callable)
    {
        //set to dispatches event variable
        $this->dispatches['event']=$event;

        //if callback is used in the normal dispatcher method.
        if(is_callable($callable)){
            $this->dispatches['callableResult']=call_user_func($callable);
        }

        //get event name by class_basename
        $this->dispatches['eventName']=lcfirst(class_basename($event));
    }

    /**
     * @return bool
     */
    protected function checkCallableResultAndParam()
    {
        //callback object and param property control.
        return isset($this->dispatches['callableResult'])
            && property_exists($this->dispatches['event'],'param');
    }

    /**
     * @param $listen
     */
    protected function eventListen($listen)
    {
        // the listening object will be resolved to the namespace value
        // in the listeners array and then the service container method via dependency injection.
        $listenNamespace=app()->namespace()->optionalListeners().'\\'.ucfirst($listen);

        // if the callback comes,
        // we will set the callback object in the param property of the event object.
        if($this->checkCallableResultAndParam()){
            $this->dispatches['event']->param=$this->dispatches['callableResult'];
        }

        // we call it with the bind property of
        // the handle method in the last stage for listen object.
        DIContainerManager::callBind([$listenNamespace,'handle'],[
            ucfirst($this->dispatches['eventName'])=>$this->dispatches['event']
        ]);
    }

    /**
     * @param $dispatcher
     * @return mixed
     */
    private function eventSubscribeHandler($dispatcher)
    {
        //get subscriber directory namespace
        $subscriberDirectory=app()->namespace()->optionalSubscribers();

        foreach ($dispatcher->subscribe as $subscribe){

            //set event object for subscribe
            $this->event=$subscribe;

            //get subscriber namespace
            $subscriberNamespace=$subscriberDirectory.'\\'.ucfirst($this->event);

            if(Utils::isNamespaceExists($subscriberNamespace)){

                // After resolving the subscriber object with the help of the service container,
                // we call the subscriber method.
                $subscriberInstance=app()->resolve($subscriberNamespace,['param'=>null]);
                $subscriberInstance->subscriber($this);
            }
        }

        //get listeners
        return $this->getListeners();
    }

    /**
     * @param $key
     * @param $listeners
     * @param callable $callable
     */
    protected function eventSubscriberProcess($key,$listeners,callable $callable)
    {
        if($key==="subscriber"){

            $event              = $this->dispatches['event'];
            $eventName          = $this->dispatches['eventName'];
            $eventCallable      = isset($this->dispatches['callableResult']);

            // If subscriber method exists; in this case
            // we are running a subscribe event.
            // If an object is returned from a callback,
            // we will bind this callback value to the method.
            $subscriberCallMethod       = $listeners[$eventName]['subscriber'][$event->param];
            $subscriberCallMethodBind   = ($eventCallable) ? $this->dispatches['callableResult'] : null;

            //We are call the specified method of the subscriber object.
            $this->dispatches['event']->$subscriberCallMethod($subscriberCallMethodBind);
        }
        else{

            //go to event listen as default
            Utils::callbackProcess($callable);
        }
    }

    /**
     * @return mixed
     */
    protected function getListeners()
    {
        if(isset(core()->events)) {
            return core()->events;
        }
        return $this->listen;
    }

    /**
     * @param EventDispatcherKernelAssigner $eventDispatcher
     */
    public function handle(EventDispatcherKernelAssigner $eventDispatcher)
    {
        //set constant event-dispatcher
        define('event-dispatcher',true);

        // we get the event dispatcher object from
        // the registered bindings object.
        // we apply this value to the registered object
        // because it is used in outgoing simple calls.
        $dispatcher=core()->bindings['eventDispatcher'];

        //get subscribe event list with event subscribe handler
        $dispatcherList=$this->eventSubscribeHandler($dispatcher);

        //we save to kernel object value of the event-dispatcher
        $eventDispatcher->setEventDispatcher($dispatcherList);
    }
}

