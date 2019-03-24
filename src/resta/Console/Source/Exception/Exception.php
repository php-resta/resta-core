<?php

namespace Resta\Console\Source\Exception;

use Resta\Router\Route;
use Resta\Support\Utils;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Resta\Foundation\PathManager\StaticPathModel;

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
        $exception = $this->argument['exception'];
        $this->argument['exceptionNamespace'] = app()->namespace()->exception();

        $this->directory['exceptionDir'] = path()->exception();


        //set project directory
        $this->file->makeDirectory($this);

        $this->touch['exception/exception'] =$this->directory['exceptionDir'].'/'.$exception.'Exception.php';

        //set project touch
        $this->file->touch($this);
        
        echo $this->classical(' > Exception called as "'.$this->argument['exception'].'" has been successfully created in the '.app()->namespace()->exception().'');
    }
}