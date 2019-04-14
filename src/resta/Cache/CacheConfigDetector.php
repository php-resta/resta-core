<?php

namespace Resta\Cache;

use Resta\Foundation\ApplicationProvider;

class CacheConfigDetector extends ApplicationProvider
{
    /**
     * @var array $config
     */
    private $config = [];

    /**
     * get cache configuration variables
     *
     * CacheConfigDetector constructor.
     */
    public function __construct()
    {
        if(config('cache.default')!==null){

            $default = config('cache.default');

            $this->config['adapter']  = config('cache.stores.'.$default.'.driver') ?? exception()->invalidArgument('driver for '.$default.' within cache config is not valid');
            $this->config['path']     = config('cache.stores.'.$default.'.path') ?? path()->appResourche().'/Cache' ;
            $this->config['expire']   = config('cache.stores.'.$default.'.expire') ?? 0;
        }
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

}