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
     * @var bool
     */
    protected $projectStatus = true;

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates an event system'
    ];

    /**
     * @var $commandRule
     */
    protected $commandRule=[];

    /**
     * @method create
     * @return mixed
     */
    public function generate(){

        $this->argument['source']=StaticPathList::$events;
        $this->argument['sourcelisten']=StaticPathList::$listeners;

        $eventDispatcher=app()->namespace()->version().'\ServiceEventDispatcherController';

        $eventDispatchers=app()->makeBind(ClosureDispatcher::class,['bind'=>new $eventDispatcher])->call(function(){
            return [
              'listen'      => $this->listen,
              'subscriber'  => $this->subscribe
            ];
        });


        $this->directory['eventsDir']=app()->path()->optionalEvents();
        $this->directory['eventsListenDir']=app()->path()->optionalListeners();
        $this->directory['eventsListenSubscriberDir']=app()->path()->optionalSubscribers();

        //set project directory
        $this->file->makeDirectory($this);
        


        foreach ($eventDispatchers['listen'] as $event=>$listens){


            $this->argument['eventMain']=ucfirst($event);

            $this->argument['eventNamespace']=app()->namespace()->optionalEvents().'\\'.$this->argument['eventMain'];


            $mainFile=$this->directory['eventsDir'].'/'.ucfirst($event).'.php';

            if(!file_exists($mainFile)){
                $this->touch['event/main']=$mainFile;
            }

            //set project touch
            $this->file->touch($this);


            foreach ($listens as $listen){

                $listenFile=$this->directory['eventsListenDir'].'/'.ucfirst($listen).'.php';

                $this->argument['eventListen']=ucfirst($listen);

                if(!file_exists($listenFile)){
                    $this->touch['event/listen']=$listenFile;

                    //set project touch
                    $this->file->touch($this);
                }


            }


        }

        foreach ($eventDispatchers['subscriber'] as $subscriber){

            $eventSubscriberName=''.ucfirst($subscriber);

            $subscriberFile=$this->directory['eventsListenSubscriberDir'].'/'.$eventSubscriberName.'.php';

            $this->argument['eventSubscriber']=$eventSubscriberName;

            if(!file_exists($subscriberFile)){
                $this->touch['event/subscriber']=$subscriberFile;

                //set project touch
                $this->file->touch($this);
            }
        }


        Utils::chmod($this->optional());

        echo $this->classical(' > Your events has been successfully created in the '.app()->namespace()->optionalEvents().'');
    }
}