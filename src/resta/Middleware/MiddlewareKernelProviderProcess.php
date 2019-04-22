<?php

namespace Resta\Middleware;

use Optimus\Onion\LayerInterface;

class MiddlewareKernelProviderProcess implements LayerInterface
{
    /**
     * @var $onionType
     */
    public $onionType;

    /**
     * @var $onions
     */
    public $onions;

    /**
     * BootstrapperPeelOnionProcess constructor.
     * @param null $onionType
     * @param null $onions
     */
    public function __construct($onionType=null,$onions=null)
    {
        $this->onionType = $onionType;
        $this->onions = $onions;
    }

    /**
     * get peel
     *
     * @param $object
     * @param \Closure $next
     * @return mixed
     */
    public function peel($object, \Closure $next)
    {
        if($this->onionType=="before"){

            $object->runs[] = $this->onionsProcess();

            return $next($object);
        }

        if($this->onionType=="after"){

            $response = $next($object);

            $object->runs[] = $this->afterPeel();

            return $response;
        }
    }

    /**
     * onion process
     *
     * @return mixed
     */
    private function onionsProcess()
    {
        return array_pop($this->onions)->callBootstrapperProcess($this->onions);
    }

    /**
     * after peeling
     *
     * @return mixed|void
     */
    private function afterPeel()
    {
        if(isset(core()->bindings['middleware']) && core()->bindings['middleware']!==null){
            return core()->bindings['middleware']->after();
        }
    }
}