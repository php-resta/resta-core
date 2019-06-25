<?php

namespace Resta\Worker;

use Resta\Contracts\JobContracts;

class SupervisorJob extends JobAbstract implements JobContracts
{
    /**
     * execute job
     *
     * @return mixed|void
     */
    public function execute()
    {
        $this->isSupervisorRunning();

        $this->{$this->jobProcessor()}();

        echo $this->getWorkersForSupervisor();
    }

    /**
     * start job
     *
     * @return mixed|void
     */
    public function start()
    {
        $this->putConfigurationFile();

        $this->reReadForSupervisor();

        $this->updateForSupervisor();

        $this->startWorkerForSupervisor();
    }

    /**
     * stop job
     *
     * @return mixed|void
     */
    public function stop()
    {
        $this->stopWorkerForSupervisor();
    }

    /**
     * get status worker
     *
     * @return mixed|void
     */
    public function status()
    {
        //
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
     * @return mixed|void
     */
    public function isSupervisorRunning()
    {
        $this->process->command(config('supervisor.commands.status'));
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
command=php '.root.'/api worker start '.$this->app->get('PROJECT_NAME').' worker:'.$this->worker->getWorker().' apply:default
autostart=true
autorestart=true
user=root
numprocs=1
redirect_stderr=true
stdout_logfile='.config('supervisor.log').'/worker.log
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