<?php

namespace Resta\Core\Tests\Request\Data;

use Resta\Request\Request as RequestClient;

class RequestProvider extends RequestClient implements \ArrayAccess {

    /**
     * @return array
     */
    public function all()
    {
        return $this->inputs;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        //
    }

    /**
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed|void
     */
    public function offsetUnset($offset)
    {
        //
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset) {

        $request = $this->all();
        if(isset($request[$offset])){
            return $request[$offset];
        }
        return null;
    }

    /**
     * Dynamically access container services.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this[$key];
    }

    /**
     * Dynamically set container services.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this[$key] = $value;
    }

}