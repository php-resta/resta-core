<?php

namespace Resta\Support\AppData;

use Resta\Contracts\ServiceMiddlewareManagerContracts;

class ServiceMiddlewareManager implements ServiceMiddlewareManagerContracts
{
    /**
     * @return array
     */
    public function handle()
    {
        return [
            //'authenticate'=>'all',
            //'clientApiToken'=>'all',
        ];
    }

    /**
     * @return array
     */
    public function after()
    {
        return [];
    }

    /**
     * @return array
     */
    public function exclude()
    {
        return [
            'all'=>['hook','login','logout']
        ];
    }
}

