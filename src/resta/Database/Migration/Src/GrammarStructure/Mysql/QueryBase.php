<?php

namespace Migratio\GrammarStructure\Mysql;

use Migratio\Contract\QueryBaseContract;
use Migratio\Contract\ColumnsProcessContract;
use Migratio\GrammarStructure\Mysql\Traits\QueryStack;
use Migratio\GrammarStructure\Mysql\Traits\ColumnsProcess;

class QueryBase implements QueryBaseContract
{
    use QueryStack,ColumnsProcess;

    /**
     * @return mixed
     */
    protected function connection()
    {
        return $this->getConnection();
    }

    /**
     * @return array|mixed
     */
    public function getTables()
    {
        $tables = $this->showTables();

        $list = [];

        foreach ($tables as $key=>$resource){

            $list[$key] = $resource[0];
        }

        return $list;
    }

    /**
     * @param array $tables
     * @return ColumnsProcessContract
     */
    public function getColumns($tables=array())
    {
        $tableList = (count($tables)) ? $tables : $this->getTables();

        $list = [];

        foreach ($tableList as $table){

            if(in_array($table,$this->getTables())){
                $list[$table] = $this->showColumnsFrom($table);
            }
        }

        $this->columnsProcess($list);

        return $this;

    }

    public function createTable()
    {

    }
}

