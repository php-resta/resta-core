<?php

namespace Resta\Container;

use Resta\Foundation\ApplicationProvider;

class ContainerKernelAssignerForBind extends ApplicationProvider
{
    /**
     * @param $bindClass
     * @param $callback
     * @return void
     */
    public function getAssigner($bindClass,$callback)
    {
        //global instance name and kernel object assign
        $bindClassInstanceName = $bindClass.'KernelAssigner';

        //set namespace for bind class
        $bindClassNamespace = 'Resta\\'.ucfirst($bindClass).'\\'.ucfirst($bindClassInstanceName);
        
        //we automatically load a global loaders for the bind method
        //and assign it to the object name in the kernel object with bind,
        //which you can easily use in the booted classes for kernel object assignments.
        if(class_exists($bindClassNamespace)){
            $this->app->register($bindClassInstanceName,$this->makeBind($bindClassNamespace));
        }

        //we register the bound object to the kernel bindings property.
        if(is_callable($callback)){
            if(class_exists($call = call_user_func($callback))){
                $this->app->register('bindings',$bindClass,$this->makeBind($call));
            }
        }
    }
}