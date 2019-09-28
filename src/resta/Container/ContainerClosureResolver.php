<?php

namespace Resta\Container;

use Resta\Support\JsonHandler;
use Resta\Support\SuperClosure;
use Resta\Exception\FileNotFoundException;

class ContainerClosureResolver
{
    /**
     * container resolver service json file
     *
     * @param $key
     * @return mixed|null
     *
     * @throws FileNotFoundException
     */
    public static function get($key)
    {
        if(file_exists(serviceJson())){
            JsonHandler::$file = serviceJson();
            $serviceJson = JsonHandler::get();

            if(isset($serviceJson['container'][$key])){
                if($serviceJson['container-format'][$key]=='string'){
                    return $serviceJson['container'][$key];
                }
                return SuperClosure::get($serviceJson['container'][$key]);
            }
        }

        return null;
    }
}