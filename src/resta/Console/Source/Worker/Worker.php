<?php

namespace Resta\Console\Source\Worker;

use Resta\Worker\WorkerManager;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;

class Worker extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type = 'worker';

    /**
     * @var $define
     */
    public $define = 'begins worker for application';

    /**
     * @var $commandRule
     */
    public $commandRule = [];

    /**
     * run all workers
     *
     * @param string $method
     * @param bool $break
     */
    private function allWorkers($method='start',$break=false)
    {
        foreach(app()->get('worker') as $key=>$item){
            $this->argument['worker'] = ucfirst($key);
            $this->argument[$key] = ucfirst($key);
            $this->{$method}();

            if($break) break;
        }
        exit();
    }

    /**
     * @inheritDoc
     */
    public function start()
    {
        if(is_null($this->argument['worker'])){
            $this->allWorkers();
            exit();
        }

        $workerName = $this->projectName().'-'.$this->argument['worker'];
        app()->terminate('WORKER');
        app()->register('WORKER',$workerName);
        app()->terminate('PROJECT_NAME');
        app()->register('PROJECT_NAME',strtolower($this->projectName()));
        app()->register('WORKER_START',true);
        app()->register('WORKER_STOP',false);
        app()->register('WORKER_STATUS',false);
        app()->register('WORKER_CLEAR',false);

        app()->resolve(WorkerManager::class,['args'=>$this->argument])->execute();
    }

    /**
     * @inheritDoc
     */
    public function stop()
    {
        if(is_null($this->argument['worker'])){
            $this->allWorkers('stop');
            exit();
        }

        $workerName = $this->projectName().'-'.$this->argument['worker'];
        app()->terminate('WORKER');
        app()->register('WORKER',$workerName);
        app()->terminate('PROJECT_NAME');
        app()->register('PROJECT_NAME',strtolower($this->projectName()));
        app()->register('WORKER_START',false);
        app()->register('WORKER_STOP',true);
        app()->register('WORKER_STATUS',false);
        app()->register('WORKER_CLEAR',false);

        app()->resolve(WorkerManager::class,['args'=>$this->argument])->execute();
    }

    /**
     * @inheritDoc
     */
    public function status()
    {
        if(!isset($this->argument['worker'])){
            $this->allWorkers('status',true);
            exit();
        }

        $workerName = $this->projectName().'-'.$this->argument['worker'];
        app()->register('WORKER',$workerName);
        app()->register('PROJECT_NAME',strtolower($this->projectName()));
        app()->register('WORKER_START',false);
        app()->register('WORKER_STOP',false);
        app()->register('WORKER_STATUS',true);
        app()->register('WORKER_CLEAR',false);

        app()->resolve(WorkerManager::class,['args'=>$this->argument])->execute();
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        if(is_null($this->argument['worker'])){
            $this->allWorkers('clear');
            exit();
        }

        $workerName = $this->projectName().'-'.$this->argument['worker'];
        app()->terminate('WORKER');
        app()->register('WORKER',$workerName);
        app()->terminate('PROJECT_NAME');
        app()->register('PROJECT_NAME',strtolower($this->projectName()));
        app()->register('WORKER_START',false);
        app()->register('WORKER_STOP',false);
        app()->register('WORKER_STATUS',false);
        app()->register('WORKER_CLEAR',true);

        app()->resolve(WorkerManager::class,['args'=>$this->argument])->execute();
    }

    /**
     * @inheritDoc
     */
    public function create()
    {
        if(!file_exists(app()->path()->workers())){
            $this->directory['worker'] = app()->path()->workers();
            $this->file->makeDirectory($this);
        }

        $this->argument['workerNamespace'] = app()->namespace()->workers();
        $this->argument['workerClass'] = ucfirst($this->argument['worker']).'';
        $this->argument['projectName'] = strtolower($this->projectName());

        $this->touch['worker/worker']= app()->path()->workers().'/'.$this->argument['worker'].'.php';


        $this->file->touch($this);

        echo $this->classical(' > Worker file called as "'.$this->argument['worker'].'" has been successfully created in the '.app()->path()->workers().'');
    }


}