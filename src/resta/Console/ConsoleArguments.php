<?php

namespace Resta\Console;

use Resta\Support\Utils;
use Resta\Url\UrlVersionIdentifier;
use Resta\Foundation\PathManager\StaticPathList;

trait ConsoleArguments
{
    /**
     * get arguments
     *
     * @return array
     */
    public function getArguments()
    {
        //if there is no arguments constant
        if(!defined('arguments'))  define ('arguments',['api']);

        //get psr standard console arguments
        return core()->consoleArguments = Utils::upperCase(arguments);
    }

    /**
     * get console class
     *
     * @return mixed
     */
    public function getConsoleClass()
    {
        return current($this->getArguments());
    }

    /**
     * get console class method
     *
     * @return mixed
     */
    public function getConsoleClassMethod()
    {
        return $this->getArguments()[1];
    }

    /**
     * get console class real arguments
     *
     * @return array
     */
    public function getConsoleClassRealArguments()
    {
        return array_slice($this->getArguments(),2);
    }

    /**
     * get console arguments with key
     *
     * @return array
     */
    public function getConsoleArgumentsWithKey()
    {
        //get console class real arguments
        $getConsoleClassRealArguments = $this->getConsoleClassRealArguments();

        $listKey = [];
        $listKey['project'] = null;

        if(property_exists($this,'consoleClassNamespace')){
            $listKey['class'] = strtolower(class_basename($this->consoleClassNamespace));
            $listKey['classMethod'] = strtolower($this->getConsoleClassMethod());
        }

        foreach($getConsoleClassRealArguments as $key=>$value){

            if($key=="0"){
                $listKey['project'] = $value;
            }
            else{

                $colonExplode = explode(":",$value);
                $listKey[strtolower($colonExplode[0])] = ucfirst($colonExplode[1]);
            }
        }

        //get app version
        $listKey['version'] = UrlVersionIdentifier::version();

        return $listKey;
    }

    /**
     * console class namespace
     *
     * @return string
     */
    public function consoleClassNamespace()
    {
        return 'Resta\Console\\Source\\'.$this->getConsoleClass().'\\'.$this->getConsoleClass();
    }

    /**
     * define app name for custom console
     *
     * @return void
     */
    public function defineAppnameForCustomConsole()
    {
        $arguments = $this->getArguments();

        $this->getConsoleArgumentsWithKey();

        if(isset($arguments[2])){
            $app = $arguments[2];
        }

        if(!defined('group')){
            define('group',StaticPathList::$projectPrefixGroup);
        }

        if(!defined('app') and isset($arguments[2])) define('app',isset($app) ? $app : null);
    }
}