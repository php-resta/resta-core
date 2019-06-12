<?php

namespace Resta\Console;

/**
 * @property  commandRule
 * @property  argument
 */
class ConsolePrepare
{
    /**
     * @var array $prepareBind
     */
    protected $prepareBind = [];

    /**
     * @param object $commander
     * @return array|mixed
     */
    public function prepareCommander($commander)
    {
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

        if(!is_array($rules)) $rules = [];

        foreach ($rules as $key=>$rule){

            if(!is_array($rules[$key])){
                $list[$key]=$rule;
            }
        }

        return $list;
    }
}