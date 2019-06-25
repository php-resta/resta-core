<?php

namespace Resta\Contracts;

interface JobContracts
{
    /**
     * @return void|mixed
     */
    public function execute();

    /**
     * @return mixed|void
     */
    public function start();

    /**
     * @return mixed|void
     */
    public function stop();

    /**
     * @return mixed|void
     */
    public function status();
}