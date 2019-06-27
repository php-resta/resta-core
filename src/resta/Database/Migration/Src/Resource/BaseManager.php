<?php

namespace Migratio\Resource;

use Migratio\Schema;
use Migratio\Contract\QueryBaseContract;
use Migratio\Contract\ColumnsProcessContract;

class BaseManager
{
    /**
     * @var $connection QueryBaseContract
     */
    protected $connection;

    /**
     * @var $schema Schema
     */
    protected $schema;

    /**
     * @var $config
     */
    protected $config;

    /**
     * Pulling constructor.
     * @param $schema Schema
     */
    public function __construct($schema)
    {
        $this->schema       = $schema;
        $this->config       = $this->schema->getConfig();
        $this->connection   = $this->schema->getConnection();
    }

    /**
     * @return mixed
     */
    public function getTables()
    {
        return $this->connection->getTables();
    }

    /**
     * @return ColumnsProcessContract
     */
    public function getColumns()
    {
        $tables = $this->schema->params['tables'];

        return $this->connection->getColumns($tables);
    }
}
