<?php

namespace Resta\Console\Source\Endpoint;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\StaticPathModel;
use Resta\Utils;

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

        $this->argument['methodPrefix'] = StaticPathModel::$methodPrefix;
        $this->directory['endpoint']    = $this->controller().'/'.$this->argument['service'];

        $this->file->makeDirectory($this);

        $this->touch['service/endpoint']        = $this->directory['endpoint'].'/'.$this->argument['service'].'Service.php';
        $this->touch['service/app']             = $this->directory['endpoint'].'/App.php';
        $this->touch['service/developer']       = $this->directory['endpoint'].'/Developer.php';
        $this->touch['service/conf']            = $this->directory['endpoint'].'/ServiceConf.php';
        $this->touch['service/dummy']           = $this->directory['endpoint'].'/Dummy.yaml';

        $this->file->touch($this);

        Utils::chmod($this->controller());

        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->bluePrint('Endpoint Named ['.$this->argument['service'].'] Has Been Successfully Created in the '.$this->argument['project'].'');
        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->cyan('   You can see in src/app/'.$this->argument['project'].'/'.Utils::getAppVersion($this->argument['project']).'/__Call your project   ');
        echo $this->classical('---------------------------------------------------------------------------');
    }
}