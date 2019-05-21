<?php

namespace Resta\Console\Source\Test;

use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;

class Test extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type = 'test';

    /**
     * @var $define
     */
    public $define = 'creates test file for application';

    /**
     * @var $commandRule
     */
    public $commandRule = ['test'];

    /**
     * @method generate
     * @return mixed
     */
    public function create()
    {

        if(!file_exists(app()->path()->tests())){
            $this->directory['test'] = app()->path()->tests();
            $this->file->makeDirectory($this);
        }

        $this->argument['testPath'] = app()->namespace()->tests();
        $this->argument['testNamespace'] = ucfirst($this->argument['test']);
        $this->argument['projectName'] = strtolower($this->projectName());

        $this->touch['test/test']= app()->path()->tests().'/'.ucfirst($this->argument['test']).'.php';


        $this->file->touch($this);

        echo $this->classical(' > Test file called as "'.$this->argument['test'].'" has been successfully created in the '.app()->namespace()->tests().'');
    }
}