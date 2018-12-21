<?php

namespace Resta\Console\Source\Test;

use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Resta\Support\Utils;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Test extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='test';

    /**
     * @var $define
     */
    public $define='test run on console';

    /**
     * @var $commandRule
     */
    public $commandRule=[];

    /**
     * @method generate
     * @return mixed
     */
    public function run()
    {
        $path = str_replace(root.'/','',path()->controller()).'/'.$this->argument['controller'];
        $process = new Process(array('vendor/bin/phpunit','--bootstrap','index.php',$path));
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        echo $this->classical($process->getOutput());
    }
}