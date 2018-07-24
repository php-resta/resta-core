<?php

namespace Resta\Console\Source\Middleware;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\StaticPathModel;
use Resta\Utils;

class Middleware extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='middleware';

    /**
     * @var $define
     */
    public $define='Middleware create';

    /**
     * @var $commandRule
     */
    public $commandRule=['middleware'];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $this->touch['middleware/middleware']= $this->project.'/'.Utils::getAppVersion($this->project).'/Middleware/'.$this->argument['middleware'].'.php';


        $this->file->touch($this);

        chmod($this->touch['middleware/middleware'],0777);

        echo $this->classical(' > Middleware called as "'.$this->argument['middleware'].'" has been successfully created in the '.app()->namespace()->middleware().'');

    }
}