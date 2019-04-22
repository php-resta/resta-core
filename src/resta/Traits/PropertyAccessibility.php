<?php

namespace Resta\Traits;

trait PropertyAccessibility
{
    /**
     * @param $offset
     * @return null
     */
    final public function offsetGet($offset)
    {
        return isset($this->{$offset}) ? $this->{$offset}: null;
    }

    /**
     * @param $offset
     * @param $value
     */
    final public function offsetSet($offset,$value)
    {
        $this->{$offset} = $value;
    }

    /**
     * @param $offset
     */
    final public function offsetUnset($offset) {
        unset($this->{$offset});
    }

    /**
     * @param $offset
     * @return bool
     */
    final public function offsetExists($offset) {
        return isset($this->{$offset});
    }
}