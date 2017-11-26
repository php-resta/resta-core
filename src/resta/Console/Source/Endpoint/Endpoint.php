<?php

namespace Resta\Console\Source\Endpoint;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;

class Endpoint extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='endpoint';

    /**
     * @var $define
     */
    public $define='Endpoint create';

    /**
     * @var $command_create
     */
    public $command_create='php api endpoint create [project] service:[endpoint]';

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $this->directory['endpoint']    = $this->controller().'/'.$this->argument['service'];

        $this->file->makeDirectory($this);

        $this->touch['service/endpoint']=$this->directory['endpoint'].'/GetService.php';

        $this->file->touch($this);


        return $this->blue('Endpoint Has Been Successfully Created');
    }
}