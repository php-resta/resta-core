<?php

namespace Resta\Traits;

use Resta\Foundation\PathManager\StaticPathRepository;
use Resta\Foundation\PathManager\StaticNamespaceRepository;

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