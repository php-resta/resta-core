<?php

namespace Migratio\GrammarStructure\Mysql\Traits;

trait ColumnsProcess
{
    /**
     * @var $columns
     */
    private $columns;

    /**
     * @var string
     */
    private $fieldKey='Field';

    /**
     * @param $columns
     */
    public function columnsProcess($columns)
    {
        $this->columns=$columns;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->columns;
    }

    /**
     * @param array $tables
     * @return array
     */
    public function fields($tables=array())
    {
        $list = [];

        foreach ($this->columns as $table=>$columns)
        {
            foreach ($columns as $columnData)
            {
                if(count($tables)){

                    if(in_array($table,$tables)){
                        $list[$table][] = $columnData[$this->fieldKey];
                    }
                }
                else{
                    $list[$table][] = $columnData[$this->fieldKey];
                }
            }
        }

        return $list;
    }
}

