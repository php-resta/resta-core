<?php

namespace Migratio\Resource\PushManager;

use Migratio\SchemaCapsule;
use Migratio\Resource\BaseManager;

class Pushing extends BaseManager
{
    //get pushing
    use PushingProcess;

    /**
     * @var array
     */
    protected $list = array();

    /**
     * pushing handle
     *
     * @return void|mixed
     */
    public function handle()
    {
        foreach ($this->tableFilters() as $table=>$files){

            $table = strtolower($table);

            foreach ($files as $file) {

                $getClassName = preg_replace('@(\d+)_@is','',$file);
                $className = $this->getClassName($getClassName);

                require_once ($file);

                $capsule = new SchemaCapsule($this->config,$file,$table);

                $this->list[$table][] = (new $className)->up($capsule);
            }
        }

        return $this->processHandler();
    }


}