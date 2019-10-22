<?php

namespace Resta\Schedule;

interface ScheduleInterface
{
    /**
     * @param int $hour
     * @param int $minute
     * @return void
     */
    public function daily($hour=0,$minute=0);
    
    /**
     * @param integer $day
     * @return $this
     */
    public function day($day=1);

    /**
     * @param integer $hour
     * @return $this
     */
    public function everyHour($hour=1);
    
    /**
     * @param int $minute
     * @return $this
     */
    public function everyMinute($minute=1);

    /**
     * @param mixed $hour
     * @return $this
     */
    public function hour($hour='*');

    /**
     * @param int $minute
     * @return $this
     */
    public function minute($minute=1);

    /**
     * @param mixed $month
     * @return $this
     */
    public function month($month=1);

    /**
     * @param mixed $week$month
     * @return $this
     */
    public function week($week=1);

}