<?php

namespace Resta\Traits;

use Resta\StaticPathRepository;
use Resta\StaticNamespaceRepository;

trait ApplicationPath
{
    /**
     * @return StaticPathRepository
     */
    public function path()
    {
        return new StaticPathRepository();
    }

    /**
     * @return StaticNamespaceRepository
     */
    public function namespace()
    {
        return new StaticNamespaceRepository();
    }


}