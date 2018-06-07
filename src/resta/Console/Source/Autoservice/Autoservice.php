<?php

namespace Resta\Console\Source\Autoservice;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\StaticPathModel;
use Resta\Utils;

class Autoservice extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='auto-service';

    /**
     * @var $define
     */
    public $define='Auto service';

    /**
     * @var $commandRule
     */
    public $commandRule=[];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $this->argument['methodPrefix'] = StaticPathModel::$methodPrefix;
        $this->directory['path']    = $this->autoService().'/'.$this->argument['project'];

        $this->file->makeDirectory($this,true);

        $this->touch['service/autoendpoint']        = $this->directory['path'].'/'.$this->argument['project'].'Service.php';

        $this->file->touch($this);

        Utils::chmod(root);

        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->bluePrint('Auto Endpoint Named ['.$this->argument['project'].'] Has Been Successfully Created');
        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->cyan('   You can see in src/store your auto endpoint   ');
        echo $this->classical('---------------------------------------------------------------------------');
    }
}