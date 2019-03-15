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
     * ResolveDummy constructor.
     * @param $app
     * @param $dummy
     */
    public function __construct($app,$dummy=null)
    {
        parent::__construct($app);

        $this->dummy = $dummy;
    }

    /**
     * @return null
     */
    public function getDummy()
    {
        return $this->dummy;
    }
}