<?php

namespace Resta\Role;

interface RoleInterface
{
    /**
     * @return bool
     */
    public function directly();

    /**
     * @return mixed
     */
    public function getAdapter();

    /**
     * @return mixed
     */
    public function getResource();

    /**
     * @return bool
     */
    public function make();

    /**
     * @param $name
     * @return $this
     */
    public function role($name);

    /**
     * @param $name
     * @return $this
     */
    public function routeName($name);

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