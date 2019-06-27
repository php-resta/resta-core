<?php

namespace Migratio\Contract\Foreign;

interface ForeignContract
{
    /**
     * @param $constraint
     * @return $this
     */
    public function constraint($constraint);

    /**
     * @param $key
     * @return ReferencesContract
     */
    public function key($key);
}

