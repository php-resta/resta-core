<?php

namespace Resta\Container;

use Resta\Support\Utils;
use Resta\Foundation\ApplicationProvider;

class ContainerMethodDocumentResolver extends ApplicationProvider
{
    /**
     * @var string $document
     */
    protected $document;

    /**
     * @var array $class
     */
    protected $class;

    /**
     * ContainerMethodDocumentResolver constructor.
     * @param $app
     * @param $document
     * @param array $class
     */
    public function __construct($app,$document,$class=array())
    {
        parent::__construct($app);

        $this->document = $document;
        $this->class = $class;

        // for class method,
        // if there is cache in document data, it will be saved in container.
        $this->isCacheMethod();
    }

    /**
     * detect document cache for class method
     *
     * @return void|mixed
     */
    private function isCacheMethod()
    {
        $cacheData = [];

        // if you have information about cache in
        // the document section of the method, the cache process is executed.
        if(is_string($this->document) && preg_match('#@cache\((.*?)\)\r\n#is',$this->document,$cache)){

            // if the cache information
            // with regular expression does not contain null data.
            if($cache!==null && isset($cache[1])){

                //as static we inject the name value into the cache data.
                $cacheData = ['cache'=>['name'=>Utils::encryptArrayData($this->class)]];

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
}