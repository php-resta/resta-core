<?php

namespace Resta\Provider;

use Resta\ApplicationProvider;
use Resta\Foundation\ApplicationConstructor;

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

        //application constructur service provider
        $this->app->bind('constructor',function()
        {
            return ApplicationConstructor::class;
        }
        ,true);
    }
}