<?php

namespace Resta\Console\Source\Schedule;

use Resta\Schedule\ScheduleManager;
use Resta\Support\Utils;
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

    /**
     * @return void
     */
    public function register()
    {
        $schedules = Utils::glob(app()->path()->schedule());


        if(isset($schedules[$this->argument['schedule']])){

            $scheduleNamespace = Utils::getNamespace($schedules[$this->argument['schedule']]);
            $scheduleInstance = app()->resolve($scheduleNamespace);

            $scheduleManager = new ScheduleManager();
            $scheduleInstance->when($scheduleManager);

            $cronScheduler = implode(' ',$scheduleManager->getCronScheduler());

            $command = $cronScheduler.' cd '.root.' && php api schedule run munch schedule:'.lcfirst($this->argument['schedule']).' >> /dev/null 2>&1';

            if($this->cronjob_exists($command)===false){

                $output = shell_exec('crontab -l');
                file_put_contents('/tmp/crontab.txt', $output.''.$command.''.PHP_EOL);
                exec('crontab /tmp/crontab.txt');

                echo $this->info('Cron has been added');
            }

        }


    }

    public function run()
    {
        $schedules = Utils::glob(app()->path()->schedule());

        if(isset($schedules[$this->argument['schedule']])){
            $scheduleNamespace = Utils::getNamespace($schedules[$this->argument['schedule']]);
            $scheduleInstance = app()->resolve($scheduleNamespace);

            $scheduleInstance->command();
        }
    }

    private function cronjob_exists($command){

        $cronjob_exists=false;

        exec('crontab -l', $crontab);


        if(isset($crontab)&&is_array($crontab)){

            $crontab = array_flip($crontab);

            if(isset($crontab[$command])){

                $cronjob_exists=true;

            }

        }
        return $cronjob_exists;
    }

}
