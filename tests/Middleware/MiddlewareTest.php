<?php

namespace Resta\Core\Tests\Middleware;

use Resta\Core\Tests\AbstractTest;
use Resta\Middleware\MiddlewareProvider;
use Resta\Contracts\ServiceMiddlewareManagerContracts;
use Resta\Core\Tests\Middleware\Manager\ServiceMiddlewareManager;

class MiddlewareTest extends AbstractTest
{
    /**
     * @return void|mixed
     */
    public function testCheckMiddlewareInstance()
    {
        $middleware = static::$app['middleware'];

        $this->assertInstanceOf(MiddlewareProvider::class,$middleware);
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function testSetServiceMiddleware()
    {
        $middleware = static::$app['middleware'];

        $middleware->setserviceMiddleware(ServiceMiddlewareManager::class);

        $this->assertSame(ServiceMiddlewareManager::class,$middleware->getServiceMiddleware());
        $this->assertInstanceOf(ServiceMiddlewareManagerContracts::class,static::$app->resolve($middleware->getServiceMiddleware()));
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function testMiddlewareBase()
    {
        $middleware = static::$app['middleware'];

        $middleware->setserviceMiddleware(ServiceMiddlewareManager::class);

        $middleware->setKeyOdds('endpoint','users');
        $middleware->setKeyOdds('method','index');
        $middleware->setKeyOdds('http','get');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("Authenticate",implode($show));

    }
}