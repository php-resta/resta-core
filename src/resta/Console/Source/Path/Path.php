<?php

namespace Resta\Console\Source\Path;

use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;

class Path extends ConsoleOutputter
{
    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type = 'path';

    /**
     * @var $define
     */
    public $define = 'returns path information for application';

    /**
     * @var $commandRule
     */
    public $commandRule = ['path'];

    /**
     * @return mixed|void
     */
    public function get()
    {
        $path = app()->path()->{$this->argument['path']}();
        echo $this->classical($path);
    }
}