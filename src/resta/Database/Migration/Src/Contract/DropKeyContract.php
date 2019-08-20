<?php

namespace Migratio\Contract;

interface DropKeyContract
{
    /**
     * @param $key_name
     * @return mixed
     */
    public function key($key_name);
}

