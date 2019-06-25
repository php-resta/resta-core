<?php

namespace Resta\Worker;

use Resta\Contracts\JobContracts;
use Resta\Contracts\ApplicationContracts;
use Resta\Foundation\ApplicationProvider;
use Resta\Contracts\WorkerManagerContracts;

class DefaultJob extends ApplicationProvider implements JobContracts
{
    /**
     * @var null|object
     */
    protected $worker;

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
    }

    /**
     * @return mixed|void
     */
    public function execute()
    {
        while(1){
            $this->worker->executeObject();
            $this->worker->executeClosure();
        }
    }

}