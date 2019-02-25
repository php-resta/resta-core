<?php

namespace Resta\Console\Source\Middleware;

use Resta\Support\Utils;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Resta\Foundation\PathManager\StaticPathModel;

class Middleware extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='middleware';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates a middleware file'
    ];

    /**
     * @var bool
     */
    protected $projectStatus = true;

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