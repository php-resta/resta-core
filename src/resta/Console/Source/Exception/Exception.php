<?php

namespace Resta\Console\Source\Exception;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\Router\Route;
use Resta\Foundation\StaticPathModel;
use Resta\Support\Utils;

class Exception extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='exception';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates an exception file for application'
    ];

    /**
     * @var bool
     */
    protected $projectStatus = true;

    /**
     * @var $commandRule
     */
    public $commandRule=['exception'];

    /**
     * @method create
     * @return mixed
     */
    public function create()
    {
        $exception=$this->argument['exception'];

        $this->directory['exceptionDir']=$this->optional().'/'.StaticPathModel::$optionalException;

        //set project directory
        $this->file->makeDirectory($this);

        $this->touch['exception/exception']        =$this->directory['exceptionDir'].'/'.$exception.'Exception.php';

        //set project touch
        $this->file->touch($this);
        
        echo $this->classical(' > Exception called as "'.$this->argument['exception'].'" has been successfully created in the '.app()->namespace()->optionalException().'');
    }
}