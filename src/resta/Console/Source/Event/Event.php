<?php

namespace Resta\Console\Source\Event;

use Resta\Console\ConsoleOutputter;
use Resta\Support\ClosureDispatcher;
use Resta\Console\ConsoleListAccessor;
use Resta\Foundation\PathManager\StaticPathList;

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

        $this->argument['eventNamespace']=app()->namespace()->optionalEvents().'';
        $this->argument['listenNamespace']=app()->namespace()->optionalListeners().'';
        $this->argument['subscriberNamespace']=app()->namespace()->optionalSubscribers().'';


        $eventDispatcher=app()->namespace()->version().'\ServiceEventDispatcherManager';

        $eventDispatchers=app()->resolve(ClosureDispatcher::class,['bind'=>new $eventDispatcher])->call(function(){
            return [
              'listen'      => $this->listen,
              'subscriber'  => $this->subscribe
            ];
        });


        $this->directory['eventsDir']=path()->optionalEvents();
        $this->directory['eventsListenDir']=path()->optionalListeners();
        $this->directory['eventsListenSubscriberDir']=path()->optionalSubscribers();

        //set project directory
        $this->file->makeDirectory($this);
        


        foreach ($eventDispatchers['listen'] as $event=>$listens){


            $this->argument['eventMain']=ucfirst($event);

            $this->argument['eventKeyNamespace']=app()->namespace()->optionalEvents().'\\'.$this->argument['eventMain'];

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
        echo $this->classical(' > Your events has been successfully created in the '.app()->namespace()->optionalEvents().'');
    }
}