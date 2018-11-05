<?php

namespace Resta\Console\Source\Test;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;

class Test extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='test';

    /**
     * @var $define
     */
    public $define='Test';

    /**
     * @var $command_create
     */
    public $command_create='php api test create';

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        return $this->blue('test');
    }
}