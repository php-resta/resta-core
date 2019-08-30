<?php

namespace Resta\Console\Source\Schedule;

use Resta\Schedule\ScheduleManager;
use Resta\Support\ClosureDispatcher;
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
    public $commandRule = [
        'create' => ['schedule'],
        'register' => ['schedule'],
        'run' => ['schedule'],
    ];

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

    public function list()
    {
        exec('crontab -l',$list);

        $this->table->setHeaders(['no','minute','hour','day','month','week','schedule','description']);


        foreach ($list as $key=>$item){

            if(preg_match('@.*php api schedule run '.strtolower($this->projectName()).'.*@is',$item,$result)){
                if(isset($result[0])){

                    $cron = [];

                    if(preg_match('@(.*)\scd@',$result[0],$cron)){
                        $cron = (isset($cron[1])) ? explode(' ',$cron[1]) : '';

                    }

                    $scheduleName = '';

                    if(preg_match('@schedule\:(.*?)\s@',$result[0],$scheduler)){
                        $scheduleName = (isset($scheduler[1])) ? $scheduler[1] : '';

                        $schedulerInstance = $this->scheduleInstance(ucfirst($scheduleName));
                        $description = ClosureDispatcher::bind($schedulerInstance)->call(function(){
                            return $this->description;
                        });

                    }

                    $this->table->addRow([
                        $key+1,
                        isset($cron[0]) ? $cron[0] : '',
                        isset($cron[1]) ? $cron[1] : '',
                        isset($cron[2]) ? $cron[2] : '',
                        isset($cron[3]) ? $cron[3] : '',
                        isset($cron[4]) ? $cron[4] : '',
                        $scheduleName,
                        isset($description) ? $description : '',
                    ]);
                }
            }
        }

        echo $this->table->getTable();
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

    /**
     * @param $schedule
     * @return mixed|null
     */
    private function scheduleInstance($schedule)
    {
        $schedules = Utils::glob(app()->path()->schedule());

        if(isset($schedules[$schedule])){
            $scheduleNamespace = Utils::getNamespace($schedules[$schedule]);
            return $scheduleInstance = app()->resolve($scheduleNamespace);
        }

        return null;

    }

}
