<?php

namespace Migratio\Contract\Foreign;

interface ReferencesContract
{
    /**
     * @param $table
     * @param string $field
     * @return OptionsContract
     */
    public function references($table,$field='id');
}

