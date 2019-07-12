<?php

namespace Migratio\Resource\PushManager;

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
            
            foreach ($this->list as $table =>$datas){

                foreach ($datas as $data){

                    $query = $this->queryBuilder($table,$data);

                    $query = $query->handle();

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
                if(count($object->getError())){
                    return 'error : '.$object->getFile().' -> '.$object->getError()[0].'';
                }
            }
        }

        return call_user_func($callback);
    }
}