<?php

namespace Migratio\Contract;

interface UniqueContract
{
    /**
     * @param $unique_name
     * @param array $uniques
     * @return mixed
     */
    public function uniques($unique_name,$uniques=array());
}

