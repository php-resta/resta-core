<?php

namespace Resta\Foundation;

use Optimus\Onion\LayerInterface;

class BootstrapperPeelOnionProcess implements LayerInterface {

    /**
     * @var $onionType
     */
    protected $onionType;

    /**
     * @var $onions
     */
    protected $onions;

    /**
     * BootstrapperPeelOnionProcess constructor.
     * @param null $onionType
     * @param null $onions
     */
    public function __construct($onionType=null,$onions=null) {
        $this->onionType=$onionType;
        $this->onions=$onions;
    }

    /**
     * @param $object
     * @param \Closure $next
     * @return mixed
     */
    public function peel($object, \Closure $next)
    {
        if($this->onionType=="before"){

            $object->runs[] = array_pop($this->onions)->callBootstrapperProcess($this->onions);

            return $next($object);
        }
        else{

            $response = $next($object);

            $object->runs[] = 'after';

            return $response;
        }



    }


}