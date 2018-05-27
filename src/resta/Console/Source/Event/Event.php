<?php

namespace Resta\Console\Source\Event;

use Resta\ClosureDispatcher;
use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\StaticPathList;
use Resta\Utils;

class Event extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='event';

    /**
     * @var $define
     */
    public $define='Event';

    /**
     * @var $command_create
     */
    public $command_create='php api event generate [project]';

    /**
     * @method create
     * @return mixed
     */
    public function generate(){

        $this->argument['source']=StaticPathList::$events;
        $this->argument['sourcelisten']=StaticPathList::$listeners;

        $eventDispatcher=app()->namespace()->version().'\ServiceEventDispatcherController';

        $listeners=app()->makeBind(ClosureDispatcher::class,['bind'=>new $eventDispatcher])->call(function(){
            return $this->listen;
        });


        foreach ($listeners as $event=>$listens){

            $this->argument['event']=ucfirst($event);

            $this->directory['eventsDir']=$this->events();
            $this->directory['eventsListenDir']=$this->listeners();

            $mainFile=$this->directory['eventsDir'].'/'.ucfirst($event).'.php';

            if(!file_exists($mainFile)){
                $this->touch['event/main']=$mainFile;
            }


            foreach ($listens as $listen){

                $listenFile=$this->directory['eventsListenDir'].'/'.ucfirst($listen).'.php';

                $this->argument['event']=ucfirst($listen);

                if(!file_exists($listenFile)){
                    $this->touch['event/listen']=$listenFile;
                }
            }


        }


        //set project directory
        $this->file->makeDirectory($this);

        //set project touch
        $this->file->touch($this);

        Utils::chmod($this->optional());

        return $this->info('the events have been successfully generated.');
    }
}