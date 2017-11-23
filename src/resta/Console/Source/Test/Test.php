<?php

namespace Resta\Console\Source\Test;

use Resta\Console\ConsoleOutputter;
use Resta\FileProcess;
use Resta\StaticPathModel;

class Test extends ConsoleOutputter {

    /**
     * @var $argument
     */
    public  $argument;

    /**
     * @var $file
     */
    public $file;

    /**
     * @var $directory
     */
    public $directory=array();

    /**
     * Project constructor.
     * @param $argument
     */
    public function __construct($argument){

        parent::__construct();
        $this->argument=$argument;
        $this->file=new FileProcess();
    }

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        return $this->blue('test');
    }
}