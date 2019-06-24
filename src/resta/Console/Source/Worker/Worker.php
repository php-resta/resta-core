<?php

namespace Resta\Console\Source\Worker;

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
        $worker = strtolower($this->argument['worker']);

        $registeredWorkers = app()->get('worker');

        if(isset($registeredWorkers[$worker])){
            while(true){
                echo $this->classical($registeredWorkers[$worker](1));
                sleep(10);
            }
            exit();
        }

        exception()->runtime('Any worker is not available');
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