<?php

namespace Resta\Contracts;

interface ApplicationContracts {

    /**
     * @return mixed
     */
   public function console();

    /**
     * @return mixed
     */
   public function handle();

    /**
     * @param callable $callback
     * @return mixed
     */
   public function loadConfig(callable $callback);

}