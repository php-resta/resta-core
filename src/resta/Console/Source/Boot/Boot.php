<?php

namespace Resta\Console\Source\Boot;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\StaticPathModel;
use Resta\Utils;

class Boot extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='boot';

    /**
     * @var $define
     */
    public $define='Boot create';

    /**
     * @var $commandRule
     */
    public $commandRule=[];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $this->touch['main/boot']        = StaticPathModel::bootDir().'/'.$this->argument['project'].'.php';

        $this->file->touch($this);

        Utils::chmod(root);

        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->bluePrint('Booting Class Named ['.$this->argument['project'].'] Has Been Successfully Created');
        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->cyan('   You can see in src/boot your boot class   ');
        echo $this->classical('---------------------------------------------------------------------------');
    }
}