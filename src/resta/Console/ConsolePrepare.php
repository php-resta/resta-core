<?php

namespace Resta\Console;

use Resta\Contracts\ConsolePrepareContracts;

class ConsolePrepare implements ConsolePrepareContracts {

    /**
     * @var array $prepareBind
     */
    protected $prepareBind=[];

    /**
     * @param $commander
     */
    public function prepareCommander($commander){

        $commander=$commander->call(function(){
            return [
                'commandRule'=>$this->commandRule,
                'arguments'=>$this->argument,
            ];
        });

        return $this->resolveParameters($commander);
    }

    /**
     * @param $commander
     */
    protected function resolveParameters($commander){

        $commandRule=$commander['commandRule'];

        $list=[];

        foreach ($commandRule as $key=>$value){
            if(!preg_match('@\?.+@is',$value)){
                if(!isset($commander['arguments'][$value])){
                    return [
                        'status'=>false,
                        'argument'=>$value,
                    ];
                }
            }
        }

        return [
            'status'=>true,
        ];


    }
}