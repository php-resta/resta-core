<?php

namespace Resta\Core\Tests\Middleware;

use Resta\Core\Tests\AbstractTest;
use Resta\Core\Tests\Middleware\Manager\ServiceMiddlewareManager5;
use Resta\Core\Tests\Middleware\Manager\ServiceMiddlewareManager6;
use Resta\Core\Tests\Middleware\Manager\ServiceMiddlewareManager7;
use Resta\Middleware\MiddlewareProvider;
use Resta\Contracts\ServiceMiddlewareManagerContracts;
use Resta\Core\Tests\Middleware\Manager\ServiceMiddlewareManager;
use Resta\Core\Tests\Middleware\Manager\ServiceMiddlewareManager2;
use Resta\Core\Tests\Middleware\Manager\ServiceMiddlewareManager3;
use Resta\Core\Tests\Middleware\Manager\ServiceMiddlewareManager4;

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

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function testMiddlewareBase2()
    {
        $middleware = static::$app['middleware'];

        $middleware->setserviceMiddleware(ServiceMiddlewareManager2::class);
        $middleware->setKeyOdds('endpoint','users');
        $middleware->setKeyOdds('method','index');
        $middleware->setKeyOdds('http','get');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("Authenticate",implode($show));

    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function testMiddlewareBase3()
    {
        $middleware = static::$app['middleware'];

        $middleware->setserviceMiddleware(ServiceMiddlewareManager3::class);

        $middleware->setKeyOdds('endpoint','users');
        $middleware->setKeyOdds('method','index');
        $middleware->setKeyOdds('http','get');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("Authenticate",implode($show));

    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function testMiddlewareBase4()
    {
        $middleware = static::$app['middleware'];

        $middleware->setserviceMiddleware(ServiceMiddlewareManager4::class);

        $middleware->setKeyOdds('endpoint','users');
        $middleware->setKeyOdds('method','index');
        $middleware->setKeyOdds('http','get');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("Authenticate",implode($show));
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function testMiddlewareBaseExclude1()
    {
        $middleware = static::$app['middleware'];

        $middleware->setserviceMiddleware(ServiceMiddlewareManager5::class);

        $middleware->setKeyOdds('endpoint','users');
        $middleware->setKeyOdds('method','index');
        $middleware->setKeyOdds('http','get');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("",implode($show));
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function testMiddlewareBaseExclude2()
    {
        $middleware = static::$app['middleware'];

        $middleware->setserviceMiddleware(ServiceMiddlewareManager6::class);

        $middleware->setKeyOdds('endpoint','users');
        $middleware->setKeyOdds('method','index');
        $middleware->setKeyOdds('http','get');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("",implode($show));

        $middleware->setKeyOdds('endpoint','products');
        $middleware->setKeyOdds('method','index');
        $middleware->setKeyOdds('http','get');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("",implode($show));

        $middleware->setKeyOdds('endpoint','blog');
        $middleware->setKeyOdds('method','create');
        $middleware->setKeyOdds('http','get');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("",implode($show));
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function testMiddlewareBaseMultiple()
    {
        $middleware = static::$app['middleware'];

        $middleware->setserviceMiddleware(ServiceMiddlewareManager7::class);

        $middleware->setKeyOdds('endpoint','users');
        $middleware->setKeyOdds('method','index');
        $middleware->setKeyOdds('http','get');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("Mid1,Mid2",implode(",",$show));


        $middleware->setKeyOdds('endpoint','products');
        $middleware->setKeyOdds('method','index');
        $middleware->setKeyOdds('http','get');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("Mid2",implode(",",$show));

        $middleware->setKeyOdds('endpoint','products');
        $middleware->setKeyOdds('method','create');
        $middleware->setKeyOdds('http','get');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("",implode(",",$show));

        $middleware->setKeyOdds('endpoint','users');
        $middleware->setKeyOdds('method','create');
        $middleware->setKeyOdds('http','get');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("Mid2",implode(",",$show));


        $middleware->setKeyOdds('endpoint','users');
        $middleware->setKeyOdds('method','post');
        $middleware->setKeyOdds('http','get');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("",implode(",",$show));

        $middleware->setKeyOdds('endpoint','hook');
        $middleware->setKeyOdds('method','post');
        $middleware->setKeyOdds('http','get');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("",implode(",",$show));

        $middleware->setKeyOdds('endpoint','login');
        $middleware->setKeyOdds('method','post');
        $middleware->setKeyOdds('http','get');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("",implode(",",$show));

        $middleware->setKeyOdds('endpoint','logout');
        $middleware->setKeyOdds('method','post');
        $middleware->setKeyOdds('http','get');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("",implode(",",$show));

        $middleware->setKeyOdds('endpoint','orders');
        $middleware->setKeyOdds('method','update');
        $middleware->setKeyOdds('http','get');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("Mid2",implode(",",$show));

        $middleware->setKeyOdds('endpoint','orders');
        $middleware->setKeyOdds('method','update');
        $middleware->setKeyOdds('http','post');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("Mid1,Mid2",implode(",",$show));

        $middleware->setKeyOdds('endpoint','orders');
        $middleware->setKeyOdds('method','update');
        $middleware->setKeyOdds('http','delete');

        $middleware->handleMiddlewareProcess();
        $show = $middleware->getShow();

        $this->assertSame("",implode(",",$show));
    }
}