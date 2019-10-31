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
        $serviceJson = app()->containerCacheFile();
        
        if(file_exists($serviceJson)){
            JsonHandler::$file = $serviceJson;
            $serviceJson = JsonHandler::get();

            $dottedKey = explode('.',$key);

            if(count($dottedKey)==2){

                if(isset($serviceJson['container'][$dottedKey[0]][$dottedKey[1]])){
                    $arrayData = $serviceJson['container'][$dottedKey[0]][$dottedKey[1]];

                    if($serviceJson['container-format'][$dottedKey[0]][$dottedKey[1]]=='string'){
                        return $arrayData;
                    }

                    if($serviceJson['container-format'][$dottedKey[0]][$dottedKey[1]]=='closure'){
                        return SuperClosure::get($arrayData);
                    }
                }
            }
            else{

                if(isset($serviceJson['container'][$key])){
                    if($serviceJson['container-format'][$key]=='string'){
                        return $serviceJson['container'][$key];
                    }

                    if($serviceJson['container-format'][$key]=='closure'){
                        return SuperClosure::get($serviceJson['container'][$key]);
                    }
                }
            }
        }

        return null;
    }
}