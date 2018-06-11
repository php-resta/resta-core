<?php

namespace Resta\Console\Source\Service;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\StaticPathModel;
use Resta\Utils;

class Service extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='service';

    /**
     * @var $define
     */
    public $define='Service create';

    /**
     * @var array
     */
    protected $commandRule=['service','?namespace'];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $this->argument['methodPrefix'] = StaticPathModel::$methodPrefix;
        $this->directory['endpoint']    = $this->controller().'/'.$this->argument['service'];

        if(isset($this->argument['namespace'])){
            $this->argument['serviceClass']=$this->argument['namespace'].''.$this->argument['service'];
        }
        else{
            $this->argument['serviceClass']=$this->argument['service'];
        }

        $this->file->makeDirectory($this);

        $this->touch['service/endpoint']        = $this->directory['endpoint'].'/'.$this->argument['serviceClass'].'Service.php';
        $this->touch['service/app']             = $this->directory['endpoint'].'/App.php';
        $this->touch['service/developer']       = $this->directory['endpoint'].'/Developer.php';
        $this->touch['service/conf']            = $this->directory['endpoint'].'/ServiceConf.php';
        $this->touch['service/dummy']           = $this->directory['endpoint'].'/Dummy.yaml';

        $this->file->touch($this);

        Utils::chmod($this->controller());

        echo $this->info('Service Named "'.$this->argument['service'].'" Has Been Successfully Created in the '.$this->argument['project'].'');

    }
}