<?php

namespace Resta\Contracts;

interface ConfigProviderContracts
{
    /**
     * @param null $path
     * @return mixed
     */
    public function setConfig($path=null);

    /**
     * @param array $files
     * @return mixed
     */
    public function registerConfiguration($files=array());

}