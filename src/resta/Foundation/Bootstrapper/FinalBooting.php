<?php

namespace Resta\Foundation\Bootstrapper;

use Resta\Support\Utils;
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
        $this->app = $app;
        $this->boot = $boot;
    }

    /**
     * @param $boots
     * @param bool $defaultBoot
     */
    private function bootstrapper($boots,$defaultBoot=true)
    {
        //boot loop make bind calling
        foreach ($boots as $bootstrapperKey=>$bootstrapper){

            // for the default boot, we overwrite the bootstrapper class's bootstrapper property
            // and load it with the boot method.
            if($defaultBoot){
                $this->app->loadIfNotExistBoot([$bootstrapperKey]);
            }
            // we will use the classical method for classes
            // that will not boot from the kernel.
            else{
                if(Utils::isNamespaceExists($bootstrapper)
                    && false===isset($this->app['resolve'][$bootstrapper])){
                    $this->app->resolve($bootstrapper)->boot();
                }
            }
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

        //custom boot according to manifest bootManager
        $this->customBootManifest(function($boot){
            $this->bootstrapper($boot,false);
        });

        // and as a result we now use
        //the instance properties of our boot lists to include our implementation.
        $this->bootstrapper($defaultBoot);
    }
}