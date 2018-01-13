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
     * @var $command_create
     */
    public $command_create='php api middleware create [project] middleware:[middleware]';

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $this->touch['middleware/middleware']= $this->project.'/'.Utils::getAppVersion($this->project).'/Middleware/'.$this->argument['middleware'].'.php';


        $this->file->touch($this);

        chmod($this->touch['middleware/middleware'],0777);

        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->bluePrint('Middleware Named ['.$this->argument['middleware'].'] Has Been Successfully Created in the '.$this->argument['project'].'');
        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->cyan('   You can see in src/app/'.$this->argument['project'].'/'.Utils::getAppVersion($this->argument['project']).'/Middleware your middleware   ');
        echo $this->classical('---------------------------------------------------------------------------');
    }
}