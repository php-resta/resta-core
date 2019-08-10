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
     * @var string
     */
    protected $routeName;

    /**
     * passing directly
     *
     * @return bool
     */
    public function directly()
    {
        return true;
    }

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
     * @return bool
     */
    public function make()
    {
        $permission = $this->getPermission();

        $routeNames = $permission::roleMake($this->routeName);

        if($routeNames->count()){
            return true;
        }

        return false;
    }

    /**
     * route name for role manager
     * 
     * @param $name
     * @return $this
     */
    public function routeName($name)
    {
        $this->routeName = $name;
        
        return $this;
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

    /**
     * get permission
     *
     * @return string
     */
    private function getPermission()
    {
        return $this->getResource().'\\'.$this->getAdapter().'\Permission';
    }
}