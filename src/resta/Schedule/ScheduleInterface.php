<?php

namespace Resta\Schedule;

interface ScheduleInterface
{
    /**
     * @param callable $callback
     * @return mixed
     */
    public function everyMinute($minute=1) : int ;
}