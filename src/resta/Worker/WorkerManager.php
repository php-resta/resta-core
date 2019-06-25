<?php

namespace Resta\Worker;

use Resta\Support\Utils;
use Resta\Contracts\WorkerContracts;
use Resta\Contracts\WorkerManagerContracts;

class WorkerManager extends WorkerManagerAbstract implements WorkerManagerContracts
{
    /**
     * @var null|object
     */
    protected $resolve;

    /**
     * execute for worker
     *
     * @return void|mixed
     */
    public function execute()
    {
        //we check the presence of worker on container.
        if($this->isWorkerAvailable()){
            $this->{$this->getApply()}();
        }
    }

    /**
     * execute closure for worker
     *
     * @return void|mixed
     */
    public function executeClosure()
    {
        $this->isWorkerClosure();

        if($this->resolve instanceof \Closure){
            $resolve = $this->resolve;
            echo $resolve($this->getData()).''.PHP_EOL;
            $this->pause();
        }
    }

    /**
     * execute object for worker
     *
     * @return void|mixed
     */
    public function executeObject()
    {
        $this->isWorkerObject();

        if($this->resolve instanceof WorkerContracts){
            $this->resolve->handle();
            echo $this->getWorker().' Worker : Ok'.PHP_EOL;
            $this->pause($this->resolve->getSleep());
        }
    }

    /**
     * is worker closure
     *
     * @return void
     */
    public function isWorkerClosure()
    {
        //we check the presence of worker on container.
        if($this->isWorkerAvailable()){

            // if worker comes as an object, this object will be executed.
            // worker can have a closure function.
            if(is_callable($worker = $this->worker[$this->getWorker()])) {
                $this->resolve = $worker;
            }
        }
    }

    /**
     * is worker object
     *
     * @return void
     */
    public function isWorkerObject()
    {
        //we check the presence of worker on container.
        if($this->isWorkerAvailable()){

            // if worker comes as an object, this object will be executed.
            // worker can have a closure function.
            if(!is_callable($worker = $this->worker[$this->getWorker()]) && Utils::isNamespaceExists($worker)) {
                $this->resolve = $this->app->resolve($worker,['data'=>$this->getData()]);
            }
        }
    }
}
