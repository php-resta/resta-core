<?php

namespace Resta\Middleware;

use Resta\Foundation\ApplicationProvider;

class MiddlewareKernelAssigner extends ApplicationProvider
{
    /**
     * @return void
     */
    public function setMiddleware()
    {
        //We are logging the kernel for the middleware class and the exclude class.
        core()->middlewareClass     = $this->makeBind(app()->namespace()->serviceMiddleware());
        core()->excludeClass        = $this->makeBind(ExcludeMiddleware::class);
    }

    /**
     * @param $middleValue
     * @return void
     */
    public function pointer($middleValue)
    {
        if(isset(core()->pointer['middlewareList'])){

            $middlewareList = core()->pointer['middlewareList'];

            if(is_array($middlewareList)){
                $middlewareList = array_merge($middlewareList,[$middleValue]);
                $this->app->register('pointer','middlewareList',$middlewareList);
            }
        }
        else{
            $this->app->register('pointer','middlewareList',[$middleValue]);
        }
    }
}