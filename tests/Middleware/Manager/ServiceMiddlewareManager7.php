<?php

namespace Resta\Core\Tests\Middleware\Manager;

use Resta\Contracts\ServiceMiddlewareManagerContracts;
use Resta\Core\Tests\Middleware\Middleware\Authenticate;
use Resta\Core\Tests\Middleware\Middleware\Mid1;
use Resta\Core\Tests\Middleware\Middleware\Mid2;

class ServiceMiddlewareManager7 implements ServiceMiddlewareManagerContracts
{
    /**
     * @return array
     */
    public function handle()
    {
        return [
            Mid1::class => ['users','orders@update@post'],
            Mid2::class => ['users','products','orders@update']
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
            'all'=>['hook','login','logout'],
            Mid2::class => ['products@create','users@post'],
            Mid1::class => ['users@create','users@post']
        ];
    }
}