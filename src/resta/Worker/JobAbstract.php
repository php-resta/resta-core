<?php

namespace Resta\Worker;

use Resta\Support\Process;
use Resta\Foundation\ApplicationProvider;
use Resta\Contracts\ApplicationContracts;
use Resta\Contracts\WorkerManagerContracts;

class JobAbstract extends ApplicationProvider
{
    /**
     * @var null|object
     */
    protected $worker;

    /**
     * @var Process
     */
    protected $process;

    /**
     * DefaultJob constructor.
     *
     * @param ApplicationContracts $app
     * @param WorkerManagerContracts $worker
     */
    public function __construct(ApplicationContracts $app,WorkerManagerContracts $worker)
    {
        parent::__construct($app);

        $this->worker = $worker;

        $this->process = new Process();
    }

    /**
     * job processor
     *
     * @return mixed|void
     */
    public function jobProcessor()
    {
        if($this->app->get('WORKER_START')===true){
            return 'start';
        }

        if($this->app->get('WORKER_STOP')===true){
            return 'stop';
        }

        if($this->app->get('WORKER_STATUS')===true){
            return 'status';
        }

        if($this->app->get('WORKER_CLEAR')===true){
            return 'clear';
        }
    }

}