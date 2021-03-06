<?php

namespace Resta\Console\Source\Env;

use Resta\Support\Utils;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Resta\Foundation\PathManager\StaticPathModel;

class Env extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='env';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates an application environment file'
    ];

    /**
     * @var $commandRule
     */
    protected $commandRule='env';

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

        echo $this->classical(' > Environment file called as "'.$envFile.'" has been successfully created in the '.StaticPathModel::appPath().'');
    }
}