<?php

namespace Resta\Foundation\PathManager;

class StaticPathList
{
    /**
     * @var string
     */
    public static $appDefine='src';

    /**
     * @var string
     */
    public static $autoloadNamespace='App';

    /**
     * @var string
     */
    public static $projectPrefixGroup='Api';

    /**
     * @var string
     */
    public static $methodPrefix='Action';

    /**
     * @var string
     */
    public static $callClassPrefix='Controller';

    /**
     * @var string
     */
    public static $controllerBundleName='';

    /**
     * @var string
     */
    public static $resourceInController='Resource';

    /**
     * @var string
     */
    public static $configurationInController='Config';

    /**
     * @var null
     */
    public static $appPath=null;

    /**
     * @var $kernel
     */
    public static $kernel='Kernel';

    /**
     * @var $repository
     */
    public static $repository='Repository';

    /**
     * @var $listener
     */
    public static $listener='Listener';

    /**
     * @var $repository
     */
    public static $serviceAnnotations='ServiceAnnotationsManager';

    /**
     * @var $repository
     */
    public static $serviceMiddleware='ServiceMiddlewareManager';

    /**
     * @var $storage
     */
    public static $storage='Storage';

    /**
     * @var $controller
     */
    public static $controller='Controllers';

    /**
     * @var $platform
     */
    public static $platform='__Platform';

    /**
     * @var $sourcePath
     */
    public static $sourcePath='Source';

    /**
     * @var $sourceRequest
     */
    public static $sourceRequest='Request';

    /**
     * @var $model
     */
    public static $model='Model';

    /**
     * @var $builder
     */
    public static $builder='Builder';

    /**
     * @var $migration
     */
    public static $migration='Migrations';

    /**
     * @var $config
     */
    public static $config='Config';

    /**
     * @var string $helpers
     */
    public static $helpers='Helpers';

    /**
     * @var $config
     */
    public static $test='Tests';


    /**
     * @var $optional
     */
    public static $optional='Optional';

    /**
     * @var $events
     */
    public static $events='Events';

    /**
     * @var $listeners
     */
    public static $listeners='Listeners';

    /**
     * @var $subscribers
     */
    public static $subscribers='Subscribers';

    /**
     * @var $exception
     */
    public static $exception='Exceptions';

    /**
     * @var $job
     */
    public static $job='Job';


    /**
     * @var $webservice
     */
    public static $webservice='Webservice';

    /**
     * @var $log
     */
    public static $log='Log';


    /**
     * @var $resource
     */
    public static $resource='Resource';

    /**
     * @var $cache
     */
    public static $cache='Cache';

    /**
     * @var $language
     */
    public static $language='Language';
    /**
     * @var $session
     */
    public static $session='Session';

    /**
     * @var $middleware
     */
    public static $middleware='Middleware';

    /**
     * @var string $route
     */
    public static $route='Routes';

    /**
     * @var string
     */
    public static $request='Request';

    /**
     * @var $factory
     */
    public static $factory='Factory';

    /**
     * @var $node
     */
    public static $node='Node';

    /**
     * @var $node
     */
    public static $provider='Providers';

    /**
     * @var $once
     */
    public static $once='Once';

    /**
     * @var $command
     */
    public static $command='Command';

    /**
     * @var $stub
     */
    public static $stub='Stub';

    /**
     * @var $store
     */
    public static $store='Store';

    /**
     * @var $boot
     */
    public static $boot='Boot';

    /**
     * @var $store
     */
    public static $autoService='Autoservice';
}