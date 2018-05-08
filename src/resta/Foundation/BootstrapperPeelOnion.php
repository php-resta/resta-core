<?php

namespace Resta\Foundation;

use Optimus\Onion\Onion;

class BootstrapperPeelOnion {

    /**
     * @var array $peelings
     */
    protected $peelings=[];

    /**
     * @var $object
     */
    protected $object;

    /**
     * @var array $onionList
     */
    protected $onionList=[];

    /**
     * @var array $onions
     */
    protected $onions=[

        'middlewareGroups',
        'reflectionGroups'
    ];

    /**
     * @var array $onionTypes
     */
    protected $onionTypes=[

        'middlewareGroups'=>'before',
    ];

    /**
     * @param $onion
     * @param callable $callback
     */
    public function onionBoot($onion,callable  $callback){

        // we are globalizing the onion variable.
        $this->onionList=$onion;

        // we register the specified classes to move the onion peel property to the kernel object.
        // so we will provide a true middleware application with our peeling feature.
        return $this->onionProcess(function() use($callback){
            return call_user_func($callback);
        });
    }

    /**
     * @param callable $callback
     */
    private function onionProcess(callable $callback){

        // if the current onionlist is present in the specified onions list we will peel.
        // otherwise we will have to make a normal bootstrapper.
        if(in_array(current($this->onionList),$this->onions)){

            // If our register property succeeds,
            // we return this condition.
            if($registerOnion=$this->appRegisterForOnion()!==null){
                return $registerOnion;
            }
        }

        // normal conditions will work as a bootstrapper.
        return call_user_func($callback);
    }

    /**
     * @method onionRun
     * @param $peelings
     * @return mixed|void
     */
    public function onionRun($peelings){

        // We call the stdClass object and
        // use the peel property with this object..
        $this->object=$this->objectCaller();

        // We are removing
        // the first bootings class of the peelings object.
        $peelOnion=array_shift($peelings);

        // we are calling
        // the onion class.
        $onion=new Onion();

        // We are running our peelings property using
        // the property of the onion class.
        return $onion->layer($peelings)
            ->peel($this->object, function($object){
                $object->runs[] = 'core';
                return $object;
            });
    }

    /**
     * @return StdClass
     */
    private function objectCaller(){

        // We throw the stdClass object runs
        // and return the object.
        $object = new \StdClass;
        $object->runs = [];

        //return object
        return $object;
    }

    /**
     * @return null
     */
    private function appRegisterForOnion(){

        // We assign the first value of
        // the onionList variable to the group variable.
        $group=current($this->onionList);

        // if there is a group in the specified onionTypes list,
        // we register to the kernel object.
        if(isset($this->onionTypes[$group])){

            // If the peelings variable does not have a kernel,
            // we first assign an instance of this class to the initial value of the array.
            if(!isset(app()->singleton()->peelings)){
                app()->singleton()->bound->register('peelings','0',$this);
            }

            // we will then use the keys of
            // the peelings feature to sort and increase the last value 1.
            $keys=array_keys(app()->singleton()->peelings);

            // and since we can not peel,
            // we will include the onion property in the process class respectively and run it as before yada after.
            $bootstrapperPeelOnionProcess=new BootstrapperPeelOnionProcess($this->onionTypes[$group],$this->onionList);

            // and we assign this running onion process property to the peelings variable on the kernel.
            return app()->singleton()->bound->register('peelings',end($keys)+1,$bootstrapperPeelOnionProcess);
        }

        return null;
    }

}