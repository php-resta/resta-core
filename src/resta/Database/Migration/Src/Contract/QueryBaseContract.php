<?php

namespace Migratio\Contract;

interface QueryBaseContract {

    /**
     * @return mixed
     */
    public function getTables();

    /**
     * @param array $tables
     * @return mixed
     */
    public function getColumns($tables=array());

}