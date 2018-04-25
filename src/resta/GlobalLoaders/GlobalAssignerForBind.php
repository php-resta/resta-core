<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;

class GlobalAssignerForBind extends ApplicationProvider  {

    /**
     * @param $bindClass
     */
    public function getAssigner($bindClass){

        //set namespace for bind class
        $bindClassNamespace=__NAMESPACE__.'\\'.ucfirst($bindClass);

        //we automatically load a global loaders for the bind method
        //and assign it to the object name in the kernel object with bind,
        //which you can easily use in the booted classes for kernel object assignments.
        if(class_exists($bindClassNamespace)){

            //global instance name and kernel object assign
            $bindClassInstanceName=$bindClass.'GlobalInstance';
            $this->singleton()->{$bindClassInstanceName}=$this->makeBind($bindClassNamespace);
        }
    }

}