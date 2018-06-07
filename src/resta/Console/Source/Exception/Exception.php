<?php

namespace Resta\Console\Source\Exception;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\StaticPathModel;
use Resta\Utils;

class Exception extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='exception';

    /**
     * @var $define
     */
    public $define='Exception create';

    /**
     * @var $commandRule
     */
    public $commandRule=['exception'];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $exception=$this->argument['exception'];

        $this->directory['exceptionDir']=$this->optional().'/'.StaticPathModel::$optionalException;

        //set project directory
        $this->file->makeDirectory($this);

        $this->touch['exception/exception']        =$this->directory['exceptionDir'].'/'.$exception.'Exception.php';

        //set project touch
        $this->file->touch($this);

        Utils::chmod($this->optional());

        return $this->info('Exception Named "'.$exception.'" Has Been Successfully Created');
    }
}