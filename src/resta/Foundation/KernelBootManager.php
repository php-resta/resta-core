<?php

namespace Resta\Foundation;

use App\AppKernel;

class KernelBootManager extends AppKernel {

    /**
     * @var array $makerList
     */
    protected $makerList=[];

    /**
     * @param $maker
     * @return mixed
     */
    protected function handle($maker){

        // As a parameter, the maker variable comes as
        // the name of the list to be booted.
        if(isset($this->{$maker})){
            $this->makerList=$this->{$maker};
        }

        //revision maker
        $this->revisionMaker();

        //group name to boot
        return $this->makerList;
    }

    /**
     * @return void
     */
    private function revisionMaker(){

        if(count($this->makerList)){

            //We return to the boot list and perform a revision check.
            foreach ($this->makerList as $makerKey=>$makerValue){

                // the revision list is presented as a helper method to prevent
                // the listener application being booted from taking the entire listener individually.
                if(isset($this->revision) && isset($this->revision[$makerValue])){
                    $this->makerList[$makerKey]=$this->revision[$makerValue];
                }
            }
        }
    }
}