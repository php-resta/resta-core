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
     * @var $commandRule
     */
    public $commandRule=[];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $envFile= $this->checkGroupArgument($this->argument['project'],'');

        //key generate file
        $this->touch['main/env']= StaticPathModel::appPath().'/'.strtolower($envFile).'.yaml';

        //set key file touch
        $this->file->touch($this);

        Utils::chmod(StaticPathModel::appPath());

        echo $this->info('Your Environment File Has Been Successfully Created');
    }
}