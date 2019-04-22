<?php

namespace Resta\Contracts;

interface ServiceMiddlewareManagerContracts {

    /**
     * @return mixed
     */
    public function after();

    /**
     * @return mixed
     */
    public function exclude();

}