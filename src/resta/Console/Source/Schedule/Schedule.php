<?php

namespace Resta\Console\Source\Schedule;

use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;

class Schedule extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type = 'schedule';

    /**
     * @var $define
     */
    public $define = 'creates schedule for application';

    /**
     * @var $commandRule
     */
    public $commandRule = ['schedule'];

    /**
     * @return void
     */
    public function create(){

        $schedulePath = app()->path()->schedule();
        
        if(!file_exists($schedulePath)){
            $this->directory['schedule'] = $schedulePath;
            $this->file->makeDirectory($this);
        }

        $this->argument['scheduleNamespace'] = app()->namespace()->schedule();
        $this->argument['scheduleClass'] = ucfirst($this->argument['schedule']).'';
        $this->argument['projectName'] = strtolower($this->projectName());

        $this->touch['schedule/schedule']= $schedulePath.'/'.$this->argument['schedule'].'.php';


        $this->file->touch($this);

        echo $this->classical(' > Schedule file called as "'.$this->argument['schedule'].'" has been successfully created in the '.$schedulePath.'');
    }
    
}