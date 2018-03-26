<?php

namespace Resta\Foundation;

/**
 * Class Bootstrappers
 * @package Resta\Foundation
 */
class Bootstrappers {

    /**
     * @var $concrete null
     */
    protected $concrete;

    /**
     * @var $bootstrapper null
     */
    protected $bootstrapper;

    /**
     * @var $pusher array
     */
    protected $pusher=array();

    /**
     * @var bootstrappers array
     */
    protected $bootstrappers=[
        'devEagerConfiguration',
        'middleware',
        'booting'
    ];

    /**
     * Bootstrappers constructor.
     * @param null $concrete
     * @param array $pusher
     * @param null $bootstrapper
     */
    public function __construct($concrete=null,$pusher=array(),$bootstrapper=null) {

        //If the user sets the bootstrapper variable to true,
        //we do not do anything.
        if($bootstrapper===true){
            throw new \LogicException('bootstrapper is not available');
        }

        //The concrete object is the callback class itself that is sent to this class.
        //Once the concrete object has been assigned, we run the bootstrappers sequence
        //and include it in the application.
        //The pusher and bootstrapper variables can be sent by the user.
        $this->concrete     = $concrete;
        $this->pusher       = $pusher;
        $this->bootstrapper = $bootstrapper;

        //call bootstrapper process
        $this->callBootstrapperProcess();
    }

    /**
     * @param bool $bootstrapper
     * @return mixed
     */
    private function getBootstrappers($bootstrapper=false){

        //If a bootstrapper variable is sent as false to the installer object,
        //the bootstrapper will be assigned as false this variable directly.
        if(false===$this->bootstrapper){
            $bootstrapper=$this->bootstrapper;
        }

        //if the value to be sent as the second parameter for this method is true,
        //the values ​​in the first parameter of the method will be combined with the bootstrappers list.
        if($bootstrapper){
            $bootstrapStack=array_merge($this->pusher,$this->bootstrappers);
        }

        //Bootstrap list join check is done and list is returned.
        return (isset($bootstrapStack)) ? $bootstrapStack : $this->pusher;
    }

    /**
     * @method callBootstrapperProcess
     * @return void
     */
    private function callBootstrapperProcess(){

        //We run the bootstrap list by callback with the object specified for the content respectively.
        foreach($this->getBootstrappers(true) as $bootstrapper){
            call_user_func([$this->concrete,$bootstrapper]);
        }
    }
}