<?php

namespace Migratio\Resource;

use Migratio\Connector\Connector;

class SqlDefinitor extends Connector
{
    /**
     * @var $config
     */
    protected $config;

    /**
     * SqlDefinitor constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;

        $this->setConnector();

    }

}

