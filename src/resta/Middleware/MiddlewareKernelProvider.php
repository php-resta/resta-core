<?php

namespace Resta\Middleware;

use Optimus\Onion\Onion;
use Resta\Foundation\ApplicationProvider;

class MiddlewareKernelProvider extends ApplicationProvider
{
    /**
     * @var array $peelings
     */
    protected $peelings = [];

    /**
     * @var $object
     */
    protected $object;

    /**
     * @var array $onionList
     */
    protected $onionList = [];

    /**
     * @var array $onionTypes
     */
    protected $onionTypes=[

        'middlewareGroups' => 'before',
        'reflectionGroups' => 'core'
    ];

    /**
     * @return null
     */
    private function appRegisterForOnion()
    {
        // We assign the first value of
        // the onionList variable to the group variable.
        $group = current($this->onionList);

        // If the peelings variable does not have a kernel,
        // we first assign an instance of this class to the initial value of the array.
        if(!isset($this->app['peelings'])){
            $this->app->register('peelings','0',$this);
        }

        // we will then use the keys of
        // the peelings feature to sort and increase the last value 1.
        $keys = array_keys($this->app['peelings']);
        
        // and since we can not peel,
        // we will include the onion property in the process class respectively and run it as before yada after.
        $bootstrapperPeelOnionProcess = new MiddlewareKernelProviderProcess($this->onionTypes[$group],$this->onionList);

        // and we assign this running onion process property to the peelings variable on the kernel.
        $this->app->register('peelings',end($keys)+1,$bootstrapperPeelOnionProcess);

        //If the peelingsAfter object is not in the kernel.
        if(!isset($this->app['peelingsAfter'])){

            //we assign the last saved after object to the peelingsAfter variable independently in the kernel.
            $bootstrapperPeelAfterOnionProcess  = new MiddlewareKernelProviderProcess('after',$this->onionList);
            $this->app->register('peelingsAfter',$bootstrapperPeelAfterOnionProcess);
        }

        return true;
    }

    /**
     * @param $peelings
     * @return object
     */
    private function getPeelings($peelings)
    {
        $peelList = [];

        // We are removing
        // the first bootings class of the peelings object.
        array_shift($peelings);

        // After we check the peel objects in the list for core,
        // we hold the core objects in a row.
        foreach ($peelings as $peelKey=>$peelObje){

            if($peelObje->onionType=='core'){
                $peelList['core'][] = $peelObje;
            }
            else{
                $peelList['peel'][] = $peelObje;
            }
        }

        //set after peelings
        $peelList['peel'][] = $this->app['peelingsAfter'];

        //We return the peel list as an object.
        return (object)$peelList;
    }

    /**
     * @return \StdClass
     */
    private function objectCaller()
    {
        // We throw the stdClass object runs
        // and return the object.
        $object = new \StdClass;
        $object->runs = [];

        //return object
        return $object;
    }

    /**
     * middleware onion boot
     *
     * @param $onion
     * @param callable $callback
     * @return mixed|null
     */
    public function onionBoot($onion,callable  $callback)
    {
        // we are globalizing the onion variable.
        $this->onionList = $onion;

        // we register the specified classes to move the onion peel property to the kernel object.
        // so we will provide a true middleware application with our peeling feature.
        return $this->onionProcess(function() use($callback){
            return call_user_func($callback);
        });
    }

    /**
     * @param callable $callback
     * @return mixed|null
     */
    private function onionProcess(callable $callback)
    {
        // if the current onionlist is present in the specified onions list we will peel.
        // otherwise we will have to make a normal bootstrapper.
        if(isset($this->onionTypes[current($this->onionList)])){

            // If our register property succeeds,
            // we return this condition.
            return $this->appRegisterForOnion();
        }

        // normal conditions will work as a bootstrapper.
        return call_user_func($callback);
    }

    /**
     * @param $peelings
     * @return mixed
     */
    public function onionRun($peelings)
    {
        // We call the stdClass object and
        // use the peel property with this object..
        $this->object = $this->objectCaller();

        // we are calling
        // the onion class.
        $getPeelings = $this->getPeelings($peelings);

        // We are running our peelings property using
        // the property of the onion class.
        return (new Onion())->layer($getPeelings->peel)->peel($this->object, function($object) use($getPeelings)
        {
            // The core object is the value applied between
            // before and after as an middleware layer.
            $core = $getPeelings->core[0]->onions;
            $object->runs[] = $core[1]->callBootstrapperProcess([pos($core)]);
            return $object;
        });
    }
}