<?php

namespace Resta\Role;

interface RoleInterface
{
    /**
     * @return mixed
     */
    public function getAdapter();

    /**
     * @return mixed
     */
    public function getResource();

    /**
     * @param string $adapter
     * @return mixed
     */
    public function setAdapter($adapter='Database');

    /**
     * @param $resource
     * @return mixed
     */
    public function setResource($resource);
}