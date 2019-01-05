<?php

namespace Resta\Provider;

use Resta\ApplicationProvider;

class ServiceProvider extends  ApplicationProvider
{
    /**
     * @return void|mixed
     */
    public function handle()
    {
        if(config('kernel')!==null){
            foreach(config('kernel.providers') as $key=>$provider){
                $this->app->makeBind($provider)->boot();
            }
        }
    }
}