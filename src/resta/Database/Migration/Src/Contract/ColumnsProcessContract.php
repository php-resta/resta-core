<?php

namespace Migratio\Contract;

interface ColumnsProcessContract {

    /**
     * @return mixed
     */
    public function all();

    /**
     * @param array $tables
     * @return mixed
     */
    public function fields($tables=array());

}

