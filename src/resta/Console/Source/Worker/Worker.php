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
    public $commandRule = ['worker'];

    /**
     * @inheritDoc
     */
    public function run()
    {
        $workerName = $this->projectName().'-'.$this->argument['worker'];
        app()->register('WORKER',$workerName);
        app()->register('PROJECT_NAME',strtolower($this->projectName()));
        app()->register('WORKER_STATUS',false);

        app()->resolve(WorkerManager::class,['args'=>$this->argument])->execute();
    }

    /**
     * @inheritDoc
     */
    public function stop()
    {
        $workerName = $this->projectName().'-'.$this->argument['worker'];
        app()->register('WORKER',$workerName);
        app()->register('PROJECT_NAME',strtolower($this->projectName()));
        app()->register('WORKER_STATUS',true);

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