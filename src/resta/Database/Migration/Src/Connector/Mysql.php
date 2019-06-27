<?php

namespace Migratio\Connector;

use Migratio\GrammarStructure\Mysql\QueryBase;

class Mysql extends QueryBase
{
    /**
     * @var $instance
     */
    private static $instance;

    /**
     * @var $connection
     */
    protected $connection;

    /**
     * Mysql constructor.
     * @param $config
     */
    public function __construct($config)
    {
        if(is_null(self::$instance)){

            //get pdo dsn
            $dsn=''.$config['driver'].':host='.$config['host'].';dbname='.$config['database'].'';
            $this->connection = new \PDO($dsn, $config['user'], $config['password']);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            self::$instance=true;
        }
    }

    /**
     * @return \PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

}