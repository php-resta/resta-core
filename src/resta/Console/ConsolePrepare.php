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
     * @return array|mixed
     */
    public function prepareCommander($commander){

        $commander=$commander->call(function() use($commander){

            return [
                'commandRule'   => $this->commandRule,
                'arguments'     => $this->argument,
                'method'        => lcfirst($commander->prepareBind['methodName'])
            ];
        });

        return $this->resolveParameters($commander);
    }

    /**
     * @param $commander
     * @return array
     */
    protected function resolveParameters($commander)
    {
        if(isset($commander['commandRule'][$commander['method']])){
            $methodCommanderRule     = $commander['commandRule'][$commander['method']];
        }

        $commandRule             = (isset($methodCommanderRule)) ? $methodCommanderRule :
            $this->getDefaultCommandRules($commander['commandRule']);

        foreach ($commandRule as $key=>$value){
            if(!preg_match('@\?.+@is',$value)){
                if(!isset($commander['arguments'][$value]) OR $commander['arguments'][$value]==""){
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

    /**
     * @param $rules
     * @return array
     */
    private function getDefaultCommandRules($rules)
    {
        $list = [];

        foreach ($rules as $key=>$rule){

            if(!is_array($rules[$key])){
                $list[$key]=$rule;
            }
        }

        return $list;
    }
}