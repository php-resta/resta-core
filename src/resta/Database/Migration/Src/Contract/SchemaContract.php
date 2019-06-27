<?php

namespace Migratio\Contract;

interface SchemaContract {

    /**
     * @return mixed
     */
    public function pull();

    /**
     * @return mixed
     */
    public function push();

    /**
     * @param mixed ...$params
     * @return mixed
     */
    public function stub(...$params);
}