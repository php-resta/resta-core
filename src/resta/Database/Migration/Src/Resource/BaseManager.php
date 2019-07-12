<?php

namespace Migratio\Resource;

use Migratio\Schema;
use Migratio\Contract\QueryBaseContract;
use Migratio\Contract\ColumnsProcessContract;
use Migratio\Resource\Request\BaseRequestProcess;

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
     * @var null|object
     */
    protected $queryBuilder;

    /**
     * Pulling constructor.
     * @param $schema Schema
     */
    public function __construct($schema)
    {
        $this->schema       = $schema;
        $this->config       = $this->schema->getConfig();
        $this->connection   = $this->schema->getConnection();

        $this->queryBuilder = $this->schema->getGrammarPath().'\QueryBuilder';
    }

    /**
     * get all files
     *
     * @return array
     */
    public function getAllFiles()
    {
        BaseRequestProcess::$paths = $this->config['paths'];

        return BaseRequestProcess::getAllFiles();
    }

    /**
     * @param $file
     * @return mixed|string
     */
    protected function getClassName($file)
    {
        $className = str_replace(".php","",BaseRequestProcess::getFileName($file));

        return $className;
    }

    /**
     * @return ColumnsProcessContract
     */
    public function getColumns()
    {
        $tables = $this->schema->params['tables'];

        return $this->connection->getColumns($tables);
    }

    /**
     * get stub path
     *
     * @return string
     */
    public function getStubPath()
    {
        return __DIR__.''.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Stub';
    }

    /**
     * @return mixed
     */
    public function getTables()
    {
        return $this->connection->getTables();
    }

    /**
     * get query builder
     *
     * @param null|string $table
     * @param null|string $data
     * @return mixed
     */
    public function queryBuilder($table=null,$data=null)
    {
        $queryBuilder = $this->queryBuilder;

        return new $queryBuilder($this->schema,$table,$data);
    }

    /**
     * get table filters
     *
     * @return array
     */
    public function tableFilters()
    {
        $tables = $this->schema->params['tables'];

        $list = [];

        foreach ($this->getAllFiles() as $table=>$allFile) {

            if(count($tables)){

                if(in_array($table,$tables)){
                    $list[$table]=$allFile;
                }
            }
        }

        return (count($list)) ? $list : $this->getAllFiles();
    }

    /**
     * @param null|string $path
     * @param array $params
     * @return mixed
     */
    public function getContentFile($path,$params=array())
    {
        $dt = fopen($path, "r");
        $content = fread($dt, filesize($path));
        fclose($dt);

        foreach ($params as $key=>$value){

            $content=str_replace("__".$key."__",$value,$content);
        }

        return $content;
    }

}
