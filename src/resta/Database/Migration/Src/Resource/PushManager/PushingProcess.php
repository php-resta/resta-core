<?php

namespace Migratio\Resource\PushManager;

use Resta\Support\Generator\Generator;
use Resta\Support\Utils;

trait PushingProcess
{
    /**
     * process handler
     *
     * @return mixed|string
     */
    public function processHandler()
    {
        return $this->errorHandler(function(){
            
            $results = [];
            
            foreach ($this->list as $table =>$datas){

                foreach ($datas as $data){
                    
                    $query = $this->queryBuilder($table,$data);

                    $query = $query->handle();
                    
                    if($query===false){
                        $results[] = [];
                    }
                    else{
                        $status =($query['result']!==false) ? true : false;
                        
                        if($status){

                            $this->schema->getConnection()->registerMigration($table,$data->getFile());

                            if(app()->isLocale()){

                                $this->schema->getConnection()->generateEntity($table);

                                if(substr($table,-1)=='s'){
                                    app()->command('model create','model:'.strtolower(substr($table,0,-1)).' table:'.$table.' entity:'.$table);
                                }
                                else{
                                    app()->command('model create','model:'.strtolower($table).' table:'.$table.' entity:'.$table);
                                }
                            }
                            

                        }

                        $results[]= [
                            'success'=>$status,
                            'file'=>$data->getFile(),
                            'table'=>$table,
                            'type'=>$query['type'],
                            'syntax'=>$query['syntax'],
                            'message'=>$query['message']
                        ];
                    }
                }
            }

            return $results;
        });
    }

    /**
     * error handler
     *
     * @param callable $callback
     * @return mixed|string
     */
    public function errorHandler(callable $callback)
    {
        foreach ($this->list as $table => $objects)
        {
            foreach ($objects as $object)
            {
                $alterBinds = $object->getAlterBinds();
                
                if(!is_null($alterBinds) && count($alterBinds)>1){
                    exception()->runtime('Only one command can be applied to alter groups');
                }
                
                if(count($object->getError())){
                    exception()->runtime(''.$object->getFile().' -> '.$object->getError()[0].'');
                }
                
            }
        }

        return call_user_func($callback);
    }
}