<?php

namespace Migratio\Contract;

interface SchemaCapsuleContract {

    /**
     * @param callable $callback
     * @return mixed
     */
    public function create(callable $callback);

    /**
     * @param callable $callback
     * @return mixed
     */
    public function alter(callable $callback);


}