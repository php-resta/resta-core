<?php

namespace Resta\Contracts;

interface ApplicationContracts {

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

    /**
     * @method bind
     * @return mixed
     */
    public function bind();
}