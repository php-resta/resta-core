<?php

namespace Resta\Schedule;

interface ScheduleCommandInterface
{
    /**
     * @return void
     */
    public function command() : void ;

    /**
     * @param ScheduleInterface $schedule
     * @return void
     */
    public function when(ScheduleInterface $schedule) : void ;
}