<?php

namespace Resta\Console\Source\Autoservice;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\Foundation\StaticPathModel;
use Resta\Support\Utils;

class Autoservice extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='auto-service';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates Auto-Controller'
    ];

    /**
     * @var $commandRule
     */
    protected $commandRule=[];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $autoDirectory=explode("\\",$this->argument['project']);

        $this->argument['autoservice']=$autoDirectory[0];

        $this->argument['methodPrefix'] = StaticPathModel::$methodPrefix;
        $this->directory['path']    = $this->autoService().'/'.$autoDirectory[0];

        $this->file->makeDirectory($this,true);

        $this->touch['service/autoendpoint']        = $this->directory['path'].'/'.$autoDirectory[0].'Service.php';

        $this->file->touch($this);

        echo $this->classical(' > Auto Service called as "'.$autoDirectory[0].'" has been successfully created in the '.$this->autoService().'');
    }

    public function test()
    {
        return 'hello';
    }
}