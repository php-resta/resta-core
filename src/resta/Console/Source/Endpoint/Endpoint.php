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

        $this->touch['service/endpoint']        =$this->directory['endpoint'].'/GetService.php';
        $this->touch['service/app']             =$this->directory['endpoint'].'/App.php';
        $this->touch['service/developer']       =$this->directory['endpoint'].'/Developer.php';
        $this->touch['service/conf']            =$this->directory['endpoint'].'/ServiceConf.php';
        $this->touch['service/dummy']            =$this->directory['endpoint'].'/Dummy.yaml';

        $this->file->touch($this);


        return $this->blue('Endpoint Has Been Successfully Created');
    }
}