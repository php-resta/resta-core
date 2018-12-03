<?php

namespace Resta\Contracts;

interface Application {

    /**
     * @method booting
     * @return mixed
     */
    public function booting();

    /**
     * @method handle
     * @return mixed
     */
    public function handle();
}