<?php

namespace Resta\Worker;

use Resta\Support\Process;
use Resta\Contracts\JobContracts;
use Resta\Contracts\ApplicationContracts;
use Resta\Foundation\ApplicationProvider;
use Resta\Contracts\WorkerManagerContracts;

class SupervisorJob extends ApplicationProvider implements JobContracts
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
     * execute job
     *
     * @return mixed|void
     */
    public function execute()
    {
        $this->isSupervisorRunning();

        if($this->app->get('WORKER_STATUS')===true){

            $this->stopWorkerForSupervisor();
        }
        else{

            $this->putConfigurationFile();

            $this->reReadForSupervisor();

            $this->updateForSupervisor();

            $this->startWorkerForSupervisor();
        }

        echo $this->getWorkersForSupervisor();
    }

    /**
     * get workers for supervisor
     *
     * @return mixed
     */
    public function getWorkersForSupervisor()
    {
        return $this->process->command(config('supervisor.commands.workers'));
    }

    /**
     * check if the supervisor is or not running
     *
     * @return bool
     */
    public function isSupervisorRunning()
    {
        return $this->process->command(config('supervisor.commands.status'));
    }

    /**
     * reread for supervisor
     *
     * @return mixed
     */
    public function reReadForSupervisor()
    {
        return $this->process->command(config('supervisor.commands.reread'));
    }

    /**
     * start Worker for supervisor
     *
     * @return mixed
     */
    public function startWorkerForSupervisor()
    {
        return $this->process->command(config('supervisor.commands.start').' '.$this->app->get('WORKER').':*');
    }

    /**
     * stop worker for supervisor
     *
     * @return mixed
     */
    public function stopWorkerForSupervisor()
    {
        return $this->process->command(config('supervisor.commands.stop').' '.$this->app->get('WORKER').':*');
    }

    /**
     * put configutation file for supervisor
     *
     * @return mixed|void
     */
    public function putConfigurationFile()
    {
        $path = config('supervisor.path').'/'.$this->app->get('WORKER').'.conf';

        if(files()->exists($path)===false){
            files()->put($path,'
[program:'.$this->app()->get('WORKER').']
process_name=%(program_name)s_%(process_num)02d
command=php '.root.'/api worker run '.$this->app->get('PROJECT_NAME').' worker:'.$this->worker->getWorker().'
autostart=true
autorestart=true
user=root
numprocs=1
redirect_stderr=true
stdout_logfile='.root.'/worker.log
');
        }

    }

    /**
     * update for supervisor
     *
     * @return mixed
     */
    public function updateForSupervisor()
    {
        return $this->process->command(config('supervisor.commands.update'));
    }

}