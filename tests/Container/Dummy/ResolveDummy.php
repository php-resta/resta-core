<?php

namespace Resta\Core\Tests\Container\Dummy;

use Resta\Foundation\ApplicationProvider;

class ResolveDummy extends ApplicationProvider
{
    /**
     * @var $dummy
     */
    protected $dummy;

    /**
     * @var $counter
     */
    protected static $counter =0;

    /**
     * ResolveDummy constructor.
     * @param $app
     * @param $dummy
     */
    public function __construct($app,$dummy=null)
    {
        parent::__construct($app);

        $this->dummy = $dummy;

        static::$counter = ++static::$counter;
    }

    /**
     * @return null
     */
    public function getDummy()
    {
        return $this->dummy;
    }

    /**
     * @return int
     */
    public function getCounter()
    {
        return static::$counter;
    }
}