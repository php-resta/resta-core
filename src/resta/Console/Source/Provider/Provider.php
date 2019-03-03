<?php

namespace Resta\Console\Source\Provider;

use Resta\Support\Utils;
use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\Foundation\PathManager\StaticPathModel;

class Provider extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='boot';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates a general provider class'
    ];

    /**
     * @var $commandRule
     */
    protected $commandRule='provider';

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $bootName=explode("\\",$this->argument['project']);

        if(file_exists(app()->path()->app())){
            $this->touch['provider/provider'] = app()->path()->provider().'/'.$this->argument['provider'].'.php';
        }
        else{

            $this->argument['provider']=current($bootName);
            $this->touch['provider/provider'] = StaticPathModel::providerDir().'/'.$this->argument['provider'].'.php';
        }

        $this->file->touch($this);

        echo $this->classical('Provider Class Named ['.$this->argument['provider'].'] Has Been Successfully Created');
    }
}