<?php

namespace Resta\Foundation\Bootstrapper;

use Resta\Support\Utils;
use Resta\Support\ClosureDispatcher;
use Resta\Contracts\ApplicationContracts;

class KernelManifestManager
{
    /**
     * @var $app ApplicationContracts
     */
    protected $app;

    /**
     * @var array $makerList
     */
    protected $makerList=[];

    /**
     * @var $manifest
     */
    protected $manifest;

    /**
     * KernelManifestManager constructor.
     * @param ApplicationContracts $app
     */
    public function __construct(ApplicationContracts $app)
    {
        //default manifest is application
        $this->manifest = $this->app = $app;

        // if there is manifest propery in the resta
        // in this case,manifest property is manifest class
        if($app['isAvailableStore']){
            $this->manifest = $this->manifest->resolve("Src\Manifest");
        }

        //closure dispatcher for manifest property
        $this->manifest = ClosureDispatcher::bind($this->manifest);
    }

    /**
     * get makerList from property
     *
     * @return array
     */
    public function getMakerList()
    {
        //get makerList property
        return $this->makerList;
    }

    /**
     * @param $maker
     * @return mixed
     */
    public function handle($maker)
    {
        $app = clone $this;

        return $this->manifest->call(function() use ($maker,$app){

            // As a parameter, the maker variable comes as
            // the name of the list to be booted.
            if(isset($this->{$maker})){

                //get default maker list
                $app->setMakerList($this->{$maker});

                // we set this condition for users to boot the classes they want in the kernel groups.
                // in the manifesto, if the kernel groups method returns an class of arrays
                // then these classes will automatically join the kernel groups installation.
                if(isset($this->run) && isset($this->run[$maker]) && is_array($this->run[$maker])){

                    $appMaker = $this->run[$maker];

                    // if the makerExtend value in the manifest is a method,
                    // in this case, the method is executed instead of the object
                    if(method_exists($this,$maker)){
                        $this->{$maker}(app());
                    }

                    //we combine the kernel with the application list on the application side.
                    $kernelMakers = array_merge($this->{$maker},$this->run[$maker]);

                    // classes in the entire maker list can be uploaded on a per-user basis.
                    // if the maker is present on a method basis, then the maker list values ​​must be true or false.
                    // if one of the maker classes is false will not load this maker class.
                    foreach ($kernelMakers as $kernelMakerAbstract=>$kernelMaker) {
                        if($kernelMaker){
                            $kernelMakers[$kernelMakerAbstract] = $appMaker[$kernelMakerAbstract];
                        }
                    }

                    // save all kernel maker list.
                    $app->setMakerList($kernelMakers);
                }
            }

            // revision maker
            // group name to boot
            if(isset($this->revision)){
                $app->revisionMaker($this->revision);
            }

            return $app->getMakerList();
        });

    }

    /**
     * check revision by manifest
     *
     * @param $revision
     */
    public function revisionMaker($revision)
    {
        if(is_array($revision) && count($this->makerList)){

            //We return to the boot list and perform a revision check.
            foreach ($this->makerList as $makerKey=>$makerValue){

                // the revision list is presented as a helper method to prevent
                // the listener application being booted from taking the entire listener individually.
                if(count($revision) && isset($revision[$makerKey]) && Utils::isNamespaceExists($revision[$makerKey])){
                    $this->makerList[$makerKey] = $revision[$makerKey];

                    // if a kernel group key that is changed to revision is an actual class,
                    // we will save this class to the container object.
                    $this->app->register('revision',$makerValue,$revision[$makerKey]);
                }
            }
        }
    }

    /**
     * set a value for makerList property
     *
     * @param $maker
     */
    public function setMakerList($maker)
    {
        //set makerList property
        $this->makerList = $maker;
    }
}