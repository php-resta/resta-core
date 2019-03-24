<?php

namespace Resta\Console\Source\Command;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\Support\Utils;

class Command extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='command';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates an application commander'
    ];

    /**
     * @var $commandRule
     */
    protected $commandRule=['command'];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $this->directory['commandDir'] = path()->command();
        $this->argument['commandNamespace'] = app()->namespace()->command();


        //set project directory
        $this->file->makeDirectory($this);

        $this->touch['command/file']        = $this->directory['commandDir'].'/'.$this->argument['command'].'.php';

        $this->file->touch($this);

        echo $this->classical(' > Commander called as "'.$this->argument['command'].'" has been successfully created in the '.app()->namespace()->command().'');

    }
}