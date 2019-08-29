<?php

namespace Resta\Schedule;

class ScheduleManager implements ScheduleInterface
{
    /**
     * @var array
     */
    protected static $cronScheduler = ['*','*','*','*','*'];

    /**
     * set day for scheduler
     *
     * @param integer $day
     * @return $this
     */
    public function day($day = 1)
    {
        self::$cronScheduler[2] = $day;

        return $this;
    }
    
    /**
     * set everyHour for scheduler
     *
     * @param integer $hour
     * @return $this
     */
    public function everyHour($hour = 1)
    {
        self::$cronScheduler[0] = '*';
        self::$cronScheduler[1] = '*/'.$hour;

        return $this;
    }
    
    /**
     * set everyMinute for scheduler
     * 
     * @param int $minute
     * @return $this
     */
    public function everyMinute($minute = 1)
    {
        self::$cronScheduler[0] = '*/'.$minute.'';
        
        return $this;
    }

    /**
     * get cron scheduler
     *
     * @return array
     */
    public function getCronScheduler()
    {
        return self::$cronScheduler;
    }

    /**
     * set hour for scheduler
     *
     * @param mixed $hour
     * @return $this
     */
    public function hour($hour = '*')
    {
        self::$cronScheduler[1] = $hour;

        return $this;
    }

    /**
     * set minute for scheduler
     *
     * @param int $minute
     * @return $this
     */
    public function minute($minute = 1)
    {
        self::$cronScheduler[0] = $minute;

        return $this;
    }

    /**
     * set month for scheduler
     *
     * @param mixed $month
     * @return $this
     */
    public function month($month = 1)
    {
        self::$cronScheduler[3] = $month;

        return $this;
    }

    /**
     * set month for scheduler
     *
     * @param mixed $month
     * @return $this
     */
    public function week($week = 1)
    {
        self::$cronScheduler[4] = $week;

        return $this;
    }
}