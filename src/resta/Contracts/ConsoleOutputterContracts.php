<?php

namespace Resta\Contracts;

interface ConsoleOutputterContracts {

    /**
     * @param $commander
     * @return mixed
     */
    public function prepareCommander($commander);

    /**
     * @param array $commander
     * @return mixed
     */
    public function exception($commander=array());

}