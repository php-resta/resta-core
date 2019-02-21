<?php

namespace Resta\Foundation\Bootstrapper;

use Resta\Support\Utils;
use Resta\StaticPathList;
use Resta\Support\ClosureDispatcher;

class CustomBooter
{
    /**
     * @var null
     */
    protected $boot;

    /**
     * @var null
     */
    protected $bootNamespace;

    /**
     * @var array
     */
    protected $booterList=[];

    /**
     * @var string
     */
    protected $customBooter='originGroups';

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
     * @param $booter
     * @return array|mixed
     */
    private function addCustomBooter($booter)
    {
        //normally we will assign a variable to our booterList list.
        $booterList=$this->getBooterList($booter);

        //Now, let's get our custom boot list.
        //Let's assign the final state to our booterList list along with our custom boot list.
        foreach (array_keys($this->getBootDirectory()) as $customBoots){

            //We assign the namespace data of the bootable class to the bootNamespace property.
            $this->bootNamespace=''.StaticPathList::$boot.'\\'.$customBoots;

            //Your custom boot objects in
            //the boot directory should not be in the middlewaregroups list.
            if(false===pos($booter)->console() && !in_array($this->bootNamespace,$booterList)){
                $this->booterManifest($booter);
            }
        }

        //The booterList property combines booterList variables.
        return array_merge($booterList,$this->booterList);
    }

    /**
     * @param $booter array
     * @return mixed
     */
    public function customBootstrappers($booter)
    {
        //if the boot list belongs to the custom booter,
        //then we can boot our custom boot objects by adding them at the end of this class.
        if(array_pop($booter)==$this->customBooter){
            return $this->addCustomBooter($booter);
        }

        //If the boot list does not belong to the booter list,
        //we normally send the boot list exactly as it is.
        return $this->getBooterList($booter);
    }

    /**
     * @param $booter
     */
    private function booterManifest($booter)
    {
        //custom boot class
        $booterManifest=$this->bootNamespace;

        // We get the manifest values from the kernel.
        $manifest=pos($booter)->singleton()->manifest;

        // We check if the manifest directory exists in the BootManager class.
        // if it is present as a manifest, the booter is added to the list.
        if(isset($manifest['bootManager'][$booterManifest])){
            $this->booterList['custom'][]=$booterManifest;
        }
    }

    /**
     * @return array
     */
    private function getBootDirectory()
    {
        //Let's get our boot objects through the glob method.
        return Utils::glob(path()->bootDir());
    }

    /**
     * @param $boot
     * @return mixed
     */
    private function getBooterList($boot)
    {
        //kernel boot name
        $kernelBootName = $this->boot;

        //We specify the method call for the booter list.
        return core()->appClosureInstance
            ->call(function() use ($kernelBootName) {
                return $this->bootFire(null,$kernelBootName);
            });
    }
}