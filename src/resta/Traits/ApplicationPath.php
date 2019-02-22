<?php

namespace Resta\Traits;

use Resta\Foundation\StaticPathRepository;
use Resta\Foundation\StaticNamespaceRepository;

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