<?php

namespace Resta\Container;

use Resta\Support\Utils;
use Resta\Support\ReflectionProcess;
use Resta\Foundation\ApplicationProvider;

class ContainerResolve extends ApplicationProvider
{
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
        // if the parameter is an object and
        // this object is a service container object
        // then the parameter will bind.
        if($parameter->getType()!==null && isset($containers[$parameter->getType()->getName()])){

            // Unpack the container object and
            // bind it to the param variable.
            $parameterName = $parameter->getName();

            //get container object
            $resolveObject = $containers[$parameter->getType()->getName()];

            // if the container object is an object,
            // it is served directly without resolving it.
            $parameterResolve = (is_object($resolveObject))
                ? $resolveObject
                : app()->resolve($resolveObject);

            //return result for parameter of the container
            return [$parameterName=>$parameterResolve];
        }

        if($parameter->getType()!== NULL && Utils::isNamespaceExists($parameter->getType()->getName())){

            // Unpack the container object and
            // bind it to the param variable.
            $parameterName = $parameter->getName();
            $parameterResolve = app()->resolve($parameter->getType()->getName());

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
        [$class,$method] = [$class[0],$class[1]];

        return $this->app['reflection']($class)->reflectionMethodParams($method);
    }

    /**
     * is cache method for application route
     *
     * @param $document
     * @param array $class
     * @return array
     */
    private function isCacheMethod($document,$class=array())
    {
        $cacheData = [];

        // if you have information about cache in
        // the document section of the method, the cache process is executed.
        if(preg_match('#@cache\((.*?)\)\r\n#is',$document,$cache)){

            // if the cache information
            // with regular expression does not contain null data.
            if($cache!==null && isset($cache[1])){

                //as static we inject the name value into the cache data.
                $cacheData = ['cache'=>['name'=>Utils::encryptArrayData($class)]];

                //cache data with the help of foreach data are transferred into the cache.
                foreach(array_filter(explode(" ",$cache[1]),'strlen') as $item){

                    $items = explode("=",$item);
                    $cacheData['cache'][$items[0]] = $items[1];
                }
            }
        }

        //we save the data stored in the cacheData variable as methodCache.
        $this->app->register('containerReflection','methodCache',$cacheData);
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

        // This method is handled as cache if method cache is available.
        $this->isCacheMethod($reflection->document,$class);

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