<?php

namespace Resta\Console\Source\Env;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\StaticPathModel;
use Resta\Utils;

class Env extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='env';

    /**
     * @var $define
     */
    public $define='env create';

    /**
     * @var $command_create
     */
    public $command_create='php api env create [project]';

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        //key generate file
        $this->touch['main/env']= StaticPathModel::appPath().'/'.strtolower($this->argument['project']).'.yaml';

        //set key file touch
        $this->file->touch($this);

        return $this->blue('Your Environment File Has Been Successfully Created');
    }
}