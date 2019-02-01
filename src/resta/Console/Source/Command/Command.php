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

        $this->directory['modelDir']=$this->command();

        //set project directory
        $this->file->makeDirectory($this);

        $this->touch['command/file']        = $this->command().'/'.$this->argument['command'].'.php';

        $this->file->touch($this);

        echo $this->classical(' > Commander called as "'.$this->argument['command'].'" has been successfully created in the '.app()->namespace()->command().'');

    }
}