<?php

namespace Resta\Foundation;

use Illuminate\Support\Collection;
use Resta\Contracts\HandleContracts;
use Resta\Contracts\ApplicationContracts;

class FinalBooting implements HandleContracts
{
    /**
     * @var $app
     */
    private $app;

    /**
     * @var $boot
     */
    private $boot;

    /**
     * FinalBooting constructor.
     * @param ApplicationContracts $app
     * @param $boot
     */
    public function __construct(ApplicationContracts $app,$boot)
    {
        $this->app=$app;
        $this->boot=$boot;
    }

    /**
     * @param $boots
     * @return void|mixed
     */
    private function bootstrapper($boots)
    {
        //boot loop make bind calling
        foreach ($boots as $bootstrapper){

            //set makeBuild for kernel boots
            $this->app->makeBind($bootstrapper,$this->app->applicationProviderBinding($this->app))
                ->boot();
        }
    }

    /**
     * @param callable $callback
     * @return void|mixed
     */
    private function customBootManifest(callable $callback)
    {
        //we make custom boot
        if(isset($this->boot['custom'])){

            //get manifest for boot manager
            //$manifest=app()->singleton()->manifest['bootManager'];
            return call_user_func_array($callback,[$this->boot['custom']]);
        }
    }

    /**
     * @param $app
     * @param $boot
     * @return mixed|void
     */
    public function handle()
    {
        //we remove the custom data from the boot list and boot normally.
        $defaultBoot = Collection::make($this->boot)->except('custom')->all();

        // and as a result we now use
        //the instance properties of our boot lists to include our implementation.
        $this->bootstrapper($defaultBoot);

        //custom boot according to manifest bootManager
        $this->customBootManifest(function($boot){
            $this->bootstrapper($boot);
        });
    }
}