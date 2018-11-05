<?php

namespace Resta\Contracts;

interface EventDispatcherContracts
{
    /**
     * @param $event
     * @param null $callable
     * @return mixed
     */
    public function dispatcher($event, $callable = null);

    /**
     * @param array $listener
     * @return mixed
     */
    public function addListener($listener=array());

    /**
     * @param array $subscriber
     * @return mixed
     */
    public function addSubscriber($subscriber=array());


}