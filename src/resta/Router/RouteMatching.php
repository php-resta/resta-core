<?php

namespace Resta\Router;

use Resta\Contracts\ApplicationContracts;
use Resta\Foundation\ApplicationProvider;

class RouteMatching extends ApplicationProvider
{
    /**
     * @var null|object
     */
    protected $route;

    /**
     * RouteMatching constructor.
     *
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

        $scoredList = [];

        foreach ($patternList as $key=>$pattern){

            $patternCount = $this->getPatternRealCount($pattern);

            if(count($urlRoute)==0 && count($urlRoute)==0) return array_key_first($patternList);

            if(isset($patternCount['optional'])){
                $optionalCount = count($patternCount['default']) + count($patternCount['optional']);
            }

            if(count($urlRoute) == count($patternCount['default']) ||
                (isset($optionalCount) && count($urlRoute)>count($patternCount['default']) && $optionalCount>=count($urlRoute))
            ){

                foreach ($pattern as $pkey=>$item){

                    if($this->route->isMatchVaribleRegexPattern($item)===false){
                        if(isset($urlRoute[$pkey]) && $urlRoute[$pkey]==$item){
                            $scoredList[$key][] = 3;
                        }
                    }

                    if($this->route->isMatchVaribleRegexPattern($item) && !$this->route->isOptionalVaribleRegexPattern($item)){
                        if(isset($urlRoute[$pkey])){
                            $scoredList[$key][] = 2;
                        }
                    }

                    if($this->route->isMatchVaribleRegexPattern($item) && $this->route->isOptionalVaribleRegexPattern($item)){
                        if(isset($urlRoute[$pkey])){
                            $scoredList[$key][] = 1;
                        }
                    }
                }
            }

        }

        return $this->showKeyAccordingToScoredList($scoredList);

    }

    /**
     * get pattern real count
     *
     * @param $pattern
     * @return array
     */
    private function getPatternRealCount($pattern)
    {
        $list = [];
        $list['default'] = [];

        foreach ($pattern as $key=>$value){
            if(($this->route->isMatchVaribleRegexPattern($value)===false) || ($this->route->isMatchVaribleRegexPattern($value)
                    && !$this->route->isOptionalVaribleRegexPattern($value))){
                $list['default'][$key] = $value;
            }

            if(($this->route->isMatchVaribleRegexPattern($value)
                && $this->route->isOptionalVaribleRegexPattern($value))){
                $list['optional'][] = true;
            }
        }

        return $list;
    }

    /**
     * show key according tp scored list
     *
     * @param array $scoredList
     * @return false|int|string
     */
    private function showKeyAccordingToScoredList($scoredList=array())
    {
        $scored = [];

        foreach($scoredList as $key=>$item){
            $scored[$key] = array_sum($item);
        }

        if(count($scored)){
            $arrayCountValues = array_count_values($scored);

            if(count($scored)!==$arrayCountValues[max($scored)]){
                return array_search(max($scored),$scored);
            }

        }

        return null;

    }

    /**
     * is same on static strings
     *
     * @param $pattern
     * @return bool
     */
    private function isSameOnStaticStrings($pattern)
    {
        $route = route();

        foreach ($pattern as $key=>$item) {
            if($this->route->isMatchVaribleRegexPattern($item)===false){
                if(isset($route[$key]) && $item!==$route[$key]){
                    return false;
                }
            }
        }

        return true;
    }
}