<?php

namespace Resta\Contracts;

interface EventDispatcherContracts
{
    /**
     * @param $eventName
     * @param $abstract
     * @param $concrete
     * @return mixed
     */
    public function addListener($eventName,$abstract,$concrete);

    /**
     * @param array $subscriber
     * @return mixed
     */
    public function addSubscriber($subscriber=array());
}