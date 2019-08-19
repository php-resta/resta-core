<?php

namespace Migratio\Contract;

interface IndexContract
{
    /**
     * @param $index_name
     * @param array $indexes
     * @return mixed
     */
    public function indexes($index_name,$indexes=array());
}

