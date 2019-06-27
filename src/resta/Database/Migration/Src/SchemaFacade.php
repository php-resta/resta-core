<?php

namespace Migratio;

use Migratio\Schema;
use Migratio\Contract\SchemaFacadeContract;
use Migratio\GrammarStructure\Mysql\Wizard\Wizard;

class SchemaFacade {

    /**
     * @var $instance
     */
    protected static $instance;

    /**
     * @var $schema
     */
    protected $schema;

    /**
     * @var array
     */
    protected static $config=array();

    /**
     * @var array $tables
     */
    protected static $tables=array();

    /**
     * SchemaFacade constructor.
     */
    public function __construct($config=array())
    {
        if(count($config)){
            $this->schema=new Schema($config);
        }
    }

    /**
     * @param array $params
     * @return SchemaFacadeContract
     */
    public static function setConfig($params=array())
    {
        self::$config=$params;

        return new static();
    }

    /**
     * @param array $tables
     * @return SchemaFacadeContract
     */
    public static function tables($tables=array())
    {
        self::$tables=$tables;

        return new static();
    }

    /**
     * @param $appName
     * @return SchemaFacade
     */
    public static function getInstance()
    {
        if(is_null(self::$instance)){
            self::$instance=new self(self::$config);
        }
        return self::$instance;
    }

    /**
     * @param $appName
     * @return \Resta\Migration\Src\Schema
     */
    protected static function getSchema()
    {
        return self::getInstance()->schema;
    }

    /**
     * @return string
     */
    public static function pull()
    {
        $schema = self::getSchema();

        $schema->params['tables']=self::$tables;

        return $schema->pull();
    }

    /**
     * @return string
     */
    public static function push()
    {
        $schema = self::getSchema();

        $schema->params['tables']=self::$tables;

        return $schema->push();
    }

    /**
     * @return mixed
     */
    public function stub()
    {
        $schema = self::getSchema();

        return $schema->stub(func_get_args());
    }

}