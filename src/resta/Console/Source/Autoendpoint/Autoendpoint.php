<?php

namespace Resta\Console\Source\Autoendpoint;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;

class Autoendpoint extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='auto-endpoint';

    /**
     * @var $define
     */
    public $define='Auto endpoint';

    /**
     * @var $command_create
     */
    public $command_create='php api autoendpoint create';

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        dd($this->autoService());
        $this->argument['methodPrefix'] = StaticPathModel::$methodPrefix;
        $this->directory['endpoint']    = $this->controller().'/'.$this->argument['service'];

        $this->file->makeDirectory($this);

        $this->touch['autoservice/endpoint']        = $this->directory['endpoint'].'/'.$this->argument['service'].'Service.php';

        $this->file->touch($this);


        return $this->blue('Endpoint Has Been Successfully Created');
    }
}