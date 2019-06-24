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
    public $commandRule = ['get'=>['worker']];

    /**
     * @return mixed
     */
    public function get()
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
}