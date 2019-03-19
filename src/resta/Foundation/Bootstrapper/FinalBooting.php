<?php

namespace Resta\Foundation\Bootstrapper;

use Resta\Support\Utils;
use Illuminate\Support\Collection;
use Resta\Contracts\ApplicationContracts;
use Resta\Foundation\ApplicationProvider;

class FinalBooting extends ApplicationProvider
{
    /**
     * @var $boot array
     */
    private $boot = array();

    /**
     * FinalBooting constructor.
     *
     * @param $app
     * @param array $boot
     */
    public function __construct($app,$boot=array())
    {
        parent::__construct($app);

        $this->boot = $boot;

        $this->handle();
    }

    /**
     * application bootstrapper process
     *
     * @param array $boots
     * @param bool $defaultBoot
     */
    private function bootstrapper($boots=array(),$defaultBoot=true)
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
     * custom boot manifest process
     *
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
     * application bootstrapper handle
     *
     * @param $app
     * @param $boot
     * @return mixed|void
     */
    private function handle()
    {
        //we remove the custom data from the boot list and boot normally.
        $defaultBoot = Collection::make($this->boot)->except('custom')->all();

        //custom boot according to manifest bootManager
        $this->customBootManifest(function($boot){
            $this->bootstrapper((array)$boot,false);
        });

        // and as a result we now use
        //the instance properties of our boot lists to include our implementation.
        $this->bootstrapper((array)$defaultBoot);
    }
}