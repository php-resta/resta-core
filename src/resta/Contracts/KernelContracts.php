<?php

namespace Resta\Contracts;

interface KernelContracts {

    /**
     * @param $group
     * @param $booting
     * @param bool $onion
     * @return mixed
     */
    public function callBootstrapperProcess($group,$booting,$onion=true);

}