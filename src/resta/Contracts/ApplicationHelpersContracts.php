<?php

namespace Resta\Contracts;

interface ApplicationHelpersContracts {

    /**
     * @return StaticPathContracts
     */
    public function path();

    /**
     * @return StaticNamespaceContracts
     */
    public function namespace();
}