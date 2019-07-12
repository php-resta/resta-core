<?php

namespace Migratio\GrammarStructure\Mysql\Traits;

trait QueryStack
{
    /**
     * @return mixed
     */
    public function connection()
    {
        return $this->schema->getConnection();
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

}

