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

                $checkMigrationMain = $this->schema->getConnection()->checkMigrationMain();
                
                if($checkMigrationMain===false && isset($this->tableFilters()['Migrations'][0])){
                    $this->apply($this->tableFilters()['Migrations'][0],'migrations');
                }
                
                $checkMigration = $this->schema->getConnection()->checkMigration($table,$file);
                
                if(!$checkMigration){

                    $getClassName = preg_replace('@(\d+)_@is','',$file);
                    $className = $this->getClassName($getClassName);

                    require_once ($file);

                    $capsule = new SchemaCapsule($this->config,$file,$table);

                    $this->list[$table][] = (new $className)->up($capsule);

                    if(app()->has('arguments')){
                        app()->terminate('arguments');
                    }

                    app()->register('arguments','table',$table);
                    app()->register('arguments','connection',$this->schema->getConnection());
                }

                
            }
        }

        return $this->processHandler();
    }

    /**
     * @param $file
     * @param $table
     * @return mixed|string
     */
    public function apply($file,$table)
    {
        $getClassName = preg_replace('@(\d+)_@is','',$file);
        $className = $this->getClassName($getClassName);

        require_once ($file);

        $capsule = new SchemaCapsule($this->config,$file,$table);

        $this->list[$table][] = (new $className)->up($capsule);

        return $this->processHandler();
    }


}