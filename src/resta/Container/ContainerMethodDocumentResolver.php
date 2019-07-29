<?php

namespace Resta\Container;

use Resta\Support\Utils;
use Resta\Foundation\ApplicationProvider;

class ContainerMethodDocumentResolver extends ApplicationProvider
{
    /**
     * @var null|object
     */
    protected $reflection;

    /**
     * @var array
     */
    protected $class;

    /**
     * ContainerMethodDocumentResolver constructor.
     * 
     * @param $app
     * @param $reflection
     * @param array $class
     */
    public function __construct($app,$reflection,$class=array())
    {
        parent::__construct($app);

        $this->reflection = $reflection;
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

        if(!isset($this->class[1]) && !is_object($this->class[0])) return;

        // if you have information about cache in
        // the document section of the method, the cache process is executed.
        if($this->reflection->isAvailableMethodDocument($this->class[1],'cache')){
            
            //as static we inject the name value into the cache data.
            $cacheData = ['cache'=>['name' => Utils::encryptArrayData($this->class)]];

            //cache data with the help of foreach data are transferred into the cache.
            foreach(array_filter(explode(" ",$this->reflection->getDocumentData()),'strlen') as $item){

                $items = explode("=",$item);

                $cacheData['cache'][$items[0]] = $items[1];

                if(in_array('query',$items)){
                    $query = get($items[1],null);
                    if(!is_null($query)){
                        $cacheData['cache']['name'] = md5(sha1(
                            $cacheData['cache']['name'].'_'.$items[1].':'.$query
                        ));
                    }
                }
            }
        }

        //we save the data stored in the cacheData variable as methodCache.
        $this->app->register('cache','methodCache',$cacheData);
        $this->app->register('cache','class',$this->class);
    }
}