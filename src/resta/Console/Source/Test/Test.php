<?php

namespace Resta\Console\Source\Test;

use Resta\Console\ConsoleOutputter;

class Test extends ConsoleOutputter {

    /**
     * @var $argument
     */
    protected  $argument;

    /**
     * Project constructor.
     * @param $argument
     */
    public function __construct($argument){

        parent::__construct();
        $this->argument=$argument;
    }

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        return $this->blue('test');
    }
}