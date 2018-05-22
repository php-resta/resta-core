<?php

namespace Resta\Foundation;

use Resta\App;
use Resta\ClassAliasGroup;
use Resta\Foundation\RegisterAppBound;

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
        'devEagerGroups',
        'originGroups',
        'middlewareGroups',
        'reflectionGroups'
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

        // If you do not have a special pusher list,
        // we are peeling.
        if(count($this->pusher)=='0'){
            $this->peelings();
        }

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
     * @param array $customBootstrapers
     */
    public function callBootstrapperProcess($customBootstrapers=[]){

        //we boot the initial installs for the application.
        $this->applicationOriginBoot();

        // here we check that a special bootstrappers list will work and we identify the onion identifier.
        // we are peeling onion class by classifying onion class.
        $customBootstrapersCount        = count($customBootstrapers);
        $getBootstrappers               = $this->getBootstrappers(true);
        $getBootstrappers               = ($customBootstrapersCount) ? $customBootstrapers : $getBootstrappers;
        $onionIdentifier                = ($customBootstrapersCount) ? false : true;

        //We run the bootstrap list by callback with the object specified for the content respectively.
        foreach($getBootstrappers as $bootstrapper){
            call_user_func_array([$this->concrete,__FUNCTION__],[$bootstrapper,$this,$onionIdentifier]);
        }
    }


    /**
     * @return void
     */
    private function applicationOriginBoot(){

        //we can use this method to move an instance of the application class with the kernel object
        //and easily resolve an encrypted instance of all the kernel variables in our helper class.
        ClassAliasGroup::setAlias(App::class,'application');

        //For the application, we create the object that the register method,
        // which is the container center, is connected to by the kernel objesine register method.
        $registerAppBound=$this->concrete->makeBind(RegisterAppBound::class);
        $registerAppBound->register('bound',$registerAppBound);

        // We are saving the application class to
        // the container object for the appClass value.
        $this->concrete->kernel()->bound->register('appClass',new \application());

    }

    /**
     * @method peelings
     * @return mixed|void
     */
    private function peelings(){

        //if there are peelings
        if(isset(app()->singleton()->peelings)){

            // We send the peelings property to
            // the bootstrapperPeelOnion class.
            $peelings=app()->singleton()->peelings;
            pos($peelings)->onionRun($peelings);
        }
    }
}