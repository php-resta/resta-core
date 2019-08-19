<?php

namespace Migratio\GrammarStructure\Mysql\Traits;

use Resta\Support\Generator\Generator;
use Resta\Support\Utils;

trait QueryStack
{
    /**
     * @return mixed
     */
    public function connection()
    {
        return $this->schema->getConnection();
    }

    public function registerMigration($name)
    {
        return $this->connection()->query(
            "INSERT INTO migrations SET name='".$name."'
            ");
    }

    public function checkMigration($name)
    {
        $query = $this->connection()->query("SELECT * FROM migrations WHERE name='".$name."'")
            ->fetchAll();
        
        return $query;
    }

    /**
     * @return mixed
     */
    public function showTables()
    {
        $tables = $this->connection()->query('SHOW TABLES')->fetchAll();

        $list = [];

        foreach ($tables as $key=>$table) {
            $list[] = $table[0];
        }

        return $list;
    }

    /**
     * @param $table
     * @return mixed
     */
    public function showColumnsFrom($table)
    {
        return $this->connection()->query('SHOW FULL COLUMNS FROM '.$table)->fetchAll();
    }

    /**
     * get charset for collation
     *
     * @param $collation
     * @return mixed
     */
    public function getCharsetForCollation($collation)
    {
        $collation = $this->connection()->query('SHOW COLLATION LIKE \''.$collation.'%\'')->fetchAll();

        if(isset($collation[0])){
            return $collation[0]['Charset'];
        }

        return 'utf8';
    }

    /**
     * get table status
     *
     * @param $table
     * @return array
     */
    public function getTableStatus($table)
    {
        $status = $this->connection()->query('show table status like \''.$table.'\'')->fetchAll();

        if(isset($status[0])){
            return $status[0];
        }

        return [];
    }

    /**
     * get show indexes
     *
     * @param $table
     * @return mixed
     */
    public function showIndexes($table)
    {
        return $this->connection()->query('SHOW INDEXES FROM '.$table)->fetchAll();
    }

    /**
     * get foreign keys from table
     *
     * @param $table
     * @return array
     */
    public function getForeignKeys($table)
    {
        $config = $this->getConfig();

        $database = (isset($config['database'])) ? $config['database'] : null;

        $query = $this->connection()->query('SELECT rc.MATCH_OPTION,rc.UPDATE_RULE,rc.DELETE_RULE,r.*
        FROM information_schema.REFERENTIAL_CONSTRAINTS as rc
        LEFT JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE as r ON r.TABLE_NAME=rc.TABLE_NAME
        WHERE rc.CONSTRAINT_SCHEMA = \''.$database.'\'
        AND rc.TABLE_NAME = \''.$table.'\' AND r.REFERENCED_TABLE_NAME is not null')->fetchAll();

        if(isset($query[0])){
            return $query[0];
        }

        return [];
    }

    /**
     * set query basic
     *
     * @param $query
     * @return mixed
     */
    public function setQueryBasic($query)
    {
        try {

            $query =$this->connection()->query($query);

            return [
                'result'=>true,
                'query'=>$query,
                'message'=>null,
            ];
        }
        catch (\PDOException $exception){

            return [
                'result'=>false,
                'query'=>$query,
                'message'=>$exception->getMessage(),
            ];
        }

    }

    /**
     * @param $table
     */
    public function generateEntity($table)
    {
        if(in_array($table,$this->showTables()) || in_array($table = strtolower($table),$this->showTables())){

            $entityDirectory = path()->model().''.DIRECTORY_SEPARATOR.'Entity';

            if(!file_exists(app()->path()->model())){
                files()->makeDirectory(app()->path()->model());
            }

            if(!file_exists($entityDirectory)){
                files()->makeDirectory($entityDirectory);
            }

            $columns = $this->showColumnsFrom($table);

            $list = [];

            foreach ($columns as $column) {
                $list[] = $column['Field'];
            }

            if(!file_exists($entityDirectory.''.DIRECTORY_SEPARATOR.''.ucfirst($table))){
                $generator = new Generator($entityDirectory.''.DIRECTORY_SEPARATOR.''.ucfirst($table),$table.'');
                $generator->createClass();
            }
            else{
                $generator = new Generator($entityDirectory.''.DIRECTORY_SEPARATOR.''.ucfirst($table),$table.'');
            }


            $abstractClassPath = $entityDirectory.''.DIRECTORY_SEPARATOR.''.ucfirst($table).''.DIRECTORY_SEPARATOR.'Entity';
            $abstractNamespace = Utils::getNamespace($abstractClassPath.''.DIRECTORY_SEPARATOR.''.ucfirst($table).'Abstract');

            $generator->createClassExtend($abstractNamespace,ucfirst($table).'Abstract');

            $generator = new Generator($abstractClassPath,$table.'Abstract');

            $generator->createClass();

            $method =array_merge([
                '__construct'
            ],array_merge($list,['__get']));

            $generator->createMethod($method);

            $generator->createMethodParameters([
                '__construct' => '$query',
                '__get' => '$name'
            ]);

            $methodBodyList = [];
            $createMethodAccessibleProperty = [];
            $createMethodDocument = [];
            $createClassDocument = [];

            foreach ($list as $item) {
                $methodBodyList[$item] = 'return self::$query->'.$item.';';
                $createClassDocument[] = '@property $this '.$item;
                $createMethodAccessibleProperty[$item] = 'protected static';
                $createMethodDocument[$item] = [
                    '@return mixed'
                ];
            }

            $generator->createClassDocument($createClassDocument);

            $generator->createMethodDocument(array_merge($createMethodDocument,[
                '__construct' => [
                    ''.$table.' constructor.',
                    '@param null|object $query'
                ],
                '__get' =>[
                    'access entity object with magic method',
                    '',
                    '@param $name',
                    '@return mixed'
                ]
            ]));

            $createMethodBody = array_merge([
                '__construct' => 'self::$query = $query;',
                '__get' => 'return static::{$name}();'
            ],$methodBodyList);

            $generator->createMethodBody($createMethodBody);

            $generator->createMethodAccessibleProperty($createMethodAccessibleProperty);


            $generator->createClassProperty([
                'protected static $query;'
            ]);

            $generator->createClassPropertyDocument([
                'protected static $query' => [
                    '@var object|null'
                ]
            ]);
        }

    }

}

