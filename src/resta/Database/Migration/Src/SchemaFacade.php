<?php

namespace Migratio;

class SchemaFacade
{
    /**
     * @var null|object
     */
    protected static $instance;

    /**
     * @var null|object
     */
    protected $schema;

    /**
     * @var array
     */
    protected static $config = array();

    /**
     * @var array $tables
     */
    protected static $tables = array();

    /**
     * SchemaFacade constructor.
     * @param array $config
     */
    public function __construct($config=array())
    {
        if(count($config)){
            $this->schema = new Schema($config);
        }
    }

    /**
     * set config
     *
     * @param array $params
     * @return SchemaFacade
     */
    public static function setConfig($params=array())
    {
        self::$config = $params;

        return new static();
    }

    /**
     * get tables
     *
     * @param array $tables
     * @return SchemaFacade
     */
    public static function tables($tables=array())
    {
        self::$tables = $tables;

        return new static();
    }

    /**
     * get instance
     *
     * @return SchemaFacade|object|null
     */
    public static function getInstance()
    {
        if(is_null(self::$instance)){
            self::$instance = new self(self::$config);
        }

        return self::$instance;
    }

    /**
     * get schema
     *
     * @return Schema|object|null
     */
    protected static function getSchema()
    {
        return self::getInstance()->schema;
    }

    /**
     * migration pull
     *
     * @return Resource\PullManager\Pulling|mixed
     */
    public static function pull()
    {
        $schema = self::getSchema();

        $schema->params['tables'] = self::$tables;

        return $schema->pull();
    }

    /**
     * migration push
     *
     * @return Resource\PushManager\Pushing|mixed
     */
    public static function push()
    {
        $schema = self::getSchema();

        $schema->params['tables'] = self::$tables;

        return $schema->push();
    }

    /**
     * migration stub
     *
     * @return mixed|void
     */
    public function stub()
    {
        $schema = self::getSchema();

        return $schema->stub(func_get_args());
    }

}