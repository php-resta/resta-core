<?php

namespace Resta\Foundation;

use Resta\StaticPathModel;
use Resta\Utils;

class CustomBooter {

    /**
     * @var string
     */
    public $boot;

    /**
     * CustomBooter constructor.
     * @param $boot
     */
    public function __construct($boot) {

        //Let's assign
        //this variable to the name of our boot list.
        $this->boot=$boot;
    }

    /**
     * @param $booter array
     * @return mixed
     */
    public function customBootstrappers($booter){

        //if the boot list belongs to the middlewaregroups list,
        //then we can custom boot our custom boot objects by adding them at the end of this class.
        if(array_pop($booter)=="middlewareGroups"){
            return $this->addMiddlewareGroupsForCustomBooter($booter);
        }

        //If the boot list does not belong to the middlewaregroups list,
        //we normally send the boot list exactly as it is.
        return current($booter)->{$this->boot}();
    }

    /**
     * @param $booter
     */
    private function addMiddlewareGroupsForCustomBooter($booter){

        //normally we will assign a variable to our MiddlewareGroups list.
        $booterList=$booter[0]->{$this->boot}();

        //Now, let's get our custom boot list.
        //Let's assign the final state to our middlewaregroups list along with our custom boot list.
        foreach (array_keys($this->getBootDirectory()) as $customBoots){

            //Your custom boot objects in
            //the boot directory should not be in the middlewaregroups list.
            if(!in_array('Boot\\'.$customBoots,$booterList)){
                $booterList[]='Boot\\'.$customBoots;
            }
        }

        //return $booter
        return $booterList;

    }

    /**
     * @return array
     */
    private function getBootDirectory(){

        //Let's get our boot objects through the glob method.
        return Utils::glob(StaticPathModel::bootDir());
    }

}