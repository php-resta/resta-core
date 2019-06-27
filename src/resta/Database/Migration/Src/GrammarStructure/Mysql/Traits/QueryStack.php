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
       return $this->connection()->query('SHOW TABLES')->fetchAll();
    }

    /**
     * @param $table
     * @return mixed
     */
    public function showColumnsFrom($table)
    {
        return $this->connection()->query('SHOW COLUMNS FROM '.$table)->fetchAll();
    }

    /**
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

