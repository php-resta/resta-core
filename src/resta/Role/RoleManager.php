<?php

namespace Resta\Role;

use Resta\Foundation\ApplicationProvider;

class RoleManager extends ApplicationProvider implements RoleInterface
{
    /**
     * @var string
     */
    protected $adapter = 'Database';

    /**
     * @var string
     */
    protected $resource = 'Resta\\Role\\Resource';

    /**
     * get resource path
     *
     * @return string
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * get resource path
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * set adapter name
     *
     * @param string $adapter
     * @return RoleManager
     */
    public function setAdapter($adapter='Database')
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * set resource path
     *
     * @param $resource
     * @return void
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }
}