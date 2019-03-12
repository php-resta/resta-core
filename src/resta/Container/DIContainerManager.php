<?php

namespace Resta\Container;

use Resta\Container\ContainerResolve;
use Resta\Foundation\ApplicationProvider;

class DIContainerManager extends ApplicationProvider
{
    /**
     * @return \DI\Container
     */
    public static function callBuild()
    {
        //di-container
        return \DI\ContainerBuilder::buildDevContainer();
    }


    /**
     * @param null $class
     * @return mixed
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function resolve($class=null)
    {
        //class resolve
        if($class!==null){
            $container = self::callBuild();
            return $container->get($class);
        }
    }

    /**
     * @param null $class
     * @param array $param
     * @return mixed|null
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function make($class=null, $param=array())
    {
        if($class!==null){

            $container = self::callBuild();
            return $container->make($class,$param);
        }

        return null;
    }

    /**
     * @param null $class
     * @return null|object
     *
     * @throws \DI\DependencyException
     */
    public static function injectOnBind($class=null)
    {
        if($class!==null){

            $container = self::callBuild();
            return $container->injectOn($class);
        }

        return null;
    }

    /**
     * @param null $class
     * @param array $param
     * @return mixed
     */
    public static function callBind($class=null, $param=array())
    {
        return (app()->resolve(ContainerResolve::class))->call($class,$param,function($call){
            return self::callBuild()->call($call->class,$call->param);
        });
    }
}