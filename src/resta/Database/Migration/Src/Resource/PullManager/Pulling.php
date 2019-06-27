<?php

namespace Migratio\Resource\PullManager;

use Migratio\Schema;
use Migratio\Resource\BaseManager;
use Migratio\Contract\QueryBaseContract;
use Migratio\Resource\Request\BaseRequestProcess;
use Migratio\SchemaCapsule;

class Pulling extends BaseManager
{
    /**
     * @return mixed
     */
    public function get()
    {
        BaseRequestProcess::$paths=$this->config['paths'];

        $allFiles = BaseRequestProcess::getAllFiles();

        foreach ($allFiles as $table=>$files){

            foreach ($files as $file) {

                $className = str_replace(".php","",BaseRequestProcess::getFileName($file));
                $className = ucfirst($className);

                $require = require_once ($file);

                $up = new $className;

                $capsule = new SchemaCapsule($this->config,$file);

                dd($up->up($capsule));
            }


        }


    }
}