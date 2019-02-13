<?php

namespace Resta\GlobalLoaders;

use Resta\Foundation\ApplicationProvider;

class GlobalAssignerForBind extends ApplicationProvider
{
    /**
     * @param $bindClass
     * @param $callback
     * @return void
     */
    public function getAssigner($bindClass,$callback)
    {
        //set namespace for bind class
        $bindClassNamespace=__NAMESPACE__.'\\'.ucfirst($bindClass);

        //we automatically load a global loaders for the bind method
        //and assign it to the object name in the kernel object with bind,
        //which you can easily use in the booted classes for kernel object assignments.
        if(class_exists($bindClassNamespace)){

            //global instance name and kernel object assign
            $bindClassInstanceName=$bindClass.'GlobalInstance';
            $this->register($bindClassInstanceName,$this->makeBind($bindClassNamespace));
        }

        //we register the bound object to the kernel bindings property.
        if(is_callable($callback)){
            if(class_exists($call=call_user_func($callback))){
                $this->register('bindings',$bindClass,$this->makeBind($call));
            }
        }
    }
}