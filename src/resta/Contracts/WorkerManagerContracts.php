<?php

namespace Resta\Contracts;

interface WorkerManagerContracts
{
    /**
     * @return void|mixed
     */
    public function execute();

    /**
     * @return void|mixed
     */
    public function executeClosure();

    /**
     * @return void|mixed
     */
    public function executeObject();
}