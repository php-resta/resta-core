<?php

namespace Resta\Contracts;

interface ConfigProviderContracts
{
    /**
     * @param array $files
     * @return mixed
     */
    public function globalAssigner($files=array());

    /**
     * @param null $path
     * @return mixed
     */
    public function setConfig($path=null);
}