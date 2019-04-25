<?php

namespace Resta\Core\Tests\Middleware\Manager;

use Resta\Contracts\ServiceMiddlewareManagerContracts;
use Resta\Core\Tests\Middleware\Middleware\Authenticate;

class ServiceMiddlewareManager3 implements ServiceMiddlewareManagerContracts
{
    /**
     * @return array
     */
    public function handle()
    {
        return [
            Authenticate::class => ['users@index']
            //'clientApiToken'=>'all',
        ];
    }

    /**
     * @return array
     */
    public function after()
    {
        return [];
    }

    /**
     * @return array
     */
    public function exclude()
    {
        return [
            'all'=>['hook','login','logout']
        ];
    }
}