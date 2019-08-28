<?php

namespace Resta\Container;

use Resta\Support\Utils;
use Resta\Support\ReflectionProcess;
use Resta\Foundation\ApplicationProvider;

class ContainerResolve extends ApplicationProvider
{
    /**
     * @var null|object
     */
    private static $reflectionInstance;

    /**
     * container call process with pipeline
     *
     * @param $class
     * @param $param
     * @param callable $callback
     * @return mixed
     *
     * @throws \ReflectionException
     */
    public function call($class,$param,callable $callback)
    {
        // We use the reflection class to solve
        // the parameters of the class's methods.
        $param = $this->reflectionMethodParameters($class,$param);

        // the results of a number of processes will be given
        // before the container pipeline method is given.
        return $this->app->resolve(ContainerPipelineResolve::class)->handle(
            function() use($class,$param,$callback)
            {
                // as a result
                // we return the resolved class to the callback class
                $params = (object)['class'=>$class,'param'=>$param];
                return call_user_func_array($callback,[$params]);
            });

    }

    /**
     * check parameter for container
     *
     * @param $containers
     * @param $parameter
     * @return array
     */
    private function checkParameterForContainer($containers,$parameter)
    {
        $containerParameterNameValue = false;

        if(isset($containers[$parameter->getType()->getName()])){
            $parameterNameResolve = $parameter->getType()->getName();
            $containerParameterNameValue = true;
        }

        if(!$containerParameterNameValue && isset($containers[$parameter->getName()])){
            $parameterNameResolve = $parameter->getName();
            $containerParameterNameValue = true;
        }

        // if the parameter is an object and
        // this object is a service container object
        // then the parameter will bind.
        if($parameter->getType()!==null && $containerParameterNameValue){

            // Unpack the container object and
            // bind it to the param variable.
            $parameterName = $parameter->getName();

            //get container object
            $resolveObject = $containers[$parameterNameResolve];

            // if the container object is an object,
            // it is served directly without resolving it.
            $parameterResolve = (is_object($resolveObject))
                ? $resolveObject
                : app()->resolve($resolveObject);

            //return result for parameter of the container
            return [$parameterName=>$parameterResolve];
        }

        if($parameter->getType()!== NULL && Utils::isNamespaceExists($parameterNameResolve)){

            // Unpack the container object and
            // bind it to the param variable.
            $parameterName = $parameter->getName();
            $parameterResolve = app()->resolve($parameterNameResolve);

            //return result for parameter of the container
            return [$parameterName=>$parameterResolve];
        }

        return [];

    }

    /**
     * get reflection method
     *
     * @param $class
     * @return mixed
     */
    private function getReflectionMethod($class)
    {
        if(!isset($class[0],$class[1])){
            exception('containerResolvingMissing')
                ->runtime('Container class resolving is missing');
        }

        [$class,$method] = [$class[0],$class[1]];

        return $this->instanceReflection($this->app['reflection']($class))
            ->reflectionMethodParams($method);
    }

    /**
     * get instance reflection
     *
     * @param $instance
     * @return object|null
     */
    public function instanceReflection($instance=null)
    {
        if(is_object($instance) && is_null(static::$reflectionInstance)){
            static::$reflectionInstance = $instance;
        }

        return static::$reflectionInstance;
    }

    /**
     * reflection method parameters
     *
     * @param $class
     * @param $param
     * @return mixed
     *
     * @throws \ReflectionException
     */
    private function reflectionMethodParameters($class,$param)
    {
        $containers = [];

        //get service container objects.
        if(isset($this->app['serviceContainer'])){
            $containers = $this->app['serviceContainer'];
        }

        // With the reflection class we get the method.
        // and then we get the parameters in array.
        $reflection = $this->getReflectionMethod($class);
        $parameters = $reflection->parameters;


        // we provide the user with the container method document and take action.
        // thus, we help the methods to have a cleaner code structure.
        $this->app->resolve(ContainerMethodDocumentResolver::class,
            ['reflection'=>$this->instanceReflection(),'class'=>$class]);

        // we group the parameters into type and
        // name and bind them with the necessary logic.
        foreach ($parameters as $parameter){

            // if the parameter is an object and
            // this object is a service container object
            // then the parameter will bind.
            $checkParameterForContainer = $this->checkParameterForContainer($containers,$parameter);

            $paramMerge = array_merge($param,$checkParameterForContainer);

            // we do some useful logic bind for user benefit.
            $param = app()->resolve(GraceContainer::class,[
                'reflection' => $reflection->reflection
            ])->graceContainerBuilder($parameter,$paramMerge);

        }

        return $param;
    }
}