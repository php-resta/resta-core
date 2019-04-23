<?php

namespace Resta\Contracts;

interface ServiceMiddlewareManagerContracts
{
    /**
     * @return mixed
     */
    public function handle();

    /**
     * @return mixed
     */
    public function after();

    /**
     * @return mixed
     */
    public function exclude();

}