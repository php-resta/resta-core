<?php

namespace Resta\Router;

use Resta\Support\Arr;
use Resta\Contracts\ApplicationContracts;
use Resta\Foundation\ApplicationProvider;

class RouteMatching extends ApplicationProvider
{
    /**
     * @var null|object
     */
    protected $route;

    /**
     * @var bool
     */
    private $unset = false;

    /**
     * RouteMatching constructor.
     * @param ApplicationContracts $app
     * @param null|object $route
     */
    public function __construct(ApplicationContracts $app, $route=null)
    {
        parent::__construct($app);

        $this->route = $route;
    }

    /**
     * get route pattern resolve
     *
     * @return array|int|string
     */
    public function getPatternResolve()
    {
        $routes = $this->route->getRoutes();

        if(!isset($routes['pattern'])){
            return [];
        }

        $patterns = $routes['pattern'];
        $urlRoute = array_filter(route(),'strlen');

        $patternList = [];

        foreach($routes['data'] as $patternKey=>$routeData){
            if($routeData['http']==httpMethod()){
                $patternList[$patternKey]=$patterns[$patternKey];
            }
        }

        $patternList = $this->matchingUrlMethod($patternList,$urlRoute);

        foreach ($patternList as $key=>$pattern){

            $pattern = array_filter($pattern,'strlen');
            $diff = Arr::arrayDiffKey($pattern,$urlRoute);

            if($diff){

                $matches = true;

                foreach ($pattern as $patternKey=>$patternValue){
                    if(!$this->route->isMatchVaribleRegexPattern($patternValue)){
                        if($patternValue!==$urlRoute[$patternKey]){
                            $matches = false;
                        }
                    }
                }

                if($matches){

                    $isArrayEqual = $this->route->checkArrayEqual($patternList,$urlRoute);

                    if($isArrayEqual===null){
                        return $key;
                    }
                    return $isArrayEqual;
                }
            }

            if(count($pattern)-1 == count(route())){
                if(preg_match('@\{[a-z]+\?\}@is',end($pattern))){
                    return $key;
                }
            }
        }
    }

    /**
     * matching url method
     *
     * @param $patterns
     * @param $urlMethod
     * @return array
     */
    public function matchingUrlMethod($patterns,$urlMethod)
    {
        if(isset($urlMethod[0])){

            $list = [];

            foreach ($patterns as $key=>$pattern){

                // if the initial value of the pattern data is present
                // and the first value from urlmethod does not match
                // and does not match the custom regex variable,
                // we empty the contents of the data.
                if(isset($pattern[0])){
                    if($pattern[0] !== $urlMethod[0] && !$this->route->isMatchVaribleRegexPattern($pattern[0])){
                        $list[$key] = [];
                    }
                }

                // if the contents of the directory are not normally emptied,
                // we continue to save the list according to keyin status.
                if(!isset($list[$key])){
                    $list[$key] = $pattern;
                }

                // This is very important.
                // Route matches can be variable-based or static string-based.
                // In this case, we remove the other matches based on the static string match.
                if(isset($pattern[0]) && $pattern[0]==$urlMethod[0]){

                    // static matches will not be deleted retrospectively.
                    // this condition will check this.
                    if($this->unset===false){
                        unset($list);
                        $this->unset = true;
                    }

                    $list[$key] = $pattern;
                }
            }

            $patterns = $list;
            $this->unset = true;
        }

        return $patterns;
    }
}