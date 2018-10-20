<?php

namespace Resta\Foundation;

use Resta\ApplicationProvider;

class RegisterAppBound extends ApplicationProvider
{
    /**
     * @var $unregister
     */
    protected $unregister;

    /**
     * @var array
     */
    protected $values=[];

    /**
     * @param $key
     * @param $object
     * @param null $concrete
     * @return mixed|void
     */
    public function register($key,$object,$concrete=null)
    {
        // we assign the values ​​required
        // for register to the global value variable.
        $this->values['key']        = $key;
        $this->values['object']     = $object;
        $this->values['concrete']   = $concrete;

        // If there is an instance of the application class,
        // the register method is saved both in this example and in the global.
        if(defined('appInstance')){

            // where we will assign both the global instance
            // and the registered application object.
            $this->setAppInstance($this->singleton());
            $this->setAppInstance(resta());

            return false;
        }

        // we are just doing global instance here.
        $this->setAppInstance($this->singleton());
    }

    /**
     * @param $instance
     * @param bool $withConcrete
     */
    private function registerProcess($instance,$withConcrete=false)
    {
        // values recorded without concrete.
        // or values deleted
        if(false===$withConcrete){

            //values registered without concrete
            $instance->{$this->values['key']}=$this->values['object'];
            return false;
        }

        //values registered with concrete
        $instance->{$this->values['key']}[$this->values['object']]=$this->values['concrete'];
    }

    /**
     * @return void
     */
    private function setAppInstance($instance)
    {
        // for application instance
        // if the values ​​to be saved are to be saved without the concrete,
        // if it is an array.
        if($this->values['concrete']===null) {

            // Without concrete,
            // the saved value will be saved
            // if the it does not exist in application instance.
            if(!isset($instance->{$this->values['key']})) {
                $this->registerProcess($instance);
            }
            return false;
        }

        // We send concrete values to be recorded with concrete as true.
        // these values will be recorded as a array.
        $this->registerProcess($instance,true);
    }

    /**
     * @param $instance
     * @param $key
     * @param null $object
     * @return mixed
     */
    public function terminate($key,$object=null)
    {
        // object null is
        // sent to just terminate a key.
        if($object===null){
            unset(resta()->{$key});
            return false;
        }

        // It is used to delete
        // both key and sequence members.
        unset(resta()->{$key}[$object]);
    }
}