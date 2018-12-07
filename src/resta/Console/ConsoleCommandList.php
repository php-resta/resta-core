<?php

namespace Resta\Console;

use Resta\Utils;
use Resta\ClosureDispatcher;
use Resta\Console\Source\Autoservice\Autoservice;

class ConsoleCommandList extends ConsoleOutputter
{
    /**
     * @return mixed
     */
    public function handle()
    {
        $getCommandList = Utils::getCommandList();

        $this->table->setHeaders(['command','project','params','description']);

        $app = $this;

        foreach ($getCommandList as $command=>$runnable){

            $commandInstance = app()->makeBind($command,['argument'=>[]]);

            $className = class_basename($commandInstance);

            ClosureDispatcher::bind($commandInstance)->call(function() use($app,$className)
            {
                if(isset($this->runnableMethods)){

                    foreach ($this->runnableMethods as $method=>$description){

                        $commandRule = (isset($this->commandRule[$method])) ?
                            $app->methodCommandRule($this->commandRule[$method]) :
                            $app->methodCommandRule($this->commandRule);

                        $projectStatus = (isset($this->projectStatus)) ? 'YES' : 'NO';

                        $app->table->addRow([
                            strtolower($className).' '.$method.'',
                            $projectStatus,
                            $commandRule,
                            $description
                        ]);
                    }
                }

            });


        }

        echo $this->table->getTable();
    }

    /**
     * @param $commandRule
     * @return string
     */
    public function methodCommandRule($commandRule)
    {

        if(is_array($commandRule)){

            $list = [];

            foreach ($commandRule as $rule){
                $list[] = $rule.':['.$rule.']';
            }

            return implode(" ",$list);
        }

        return '['.$commandRule.']';
    }
}