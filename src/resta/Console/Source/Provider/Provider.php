<?php

namespace Resta\Console\Source\Provider;

use http\Exception\InvalidArgumentException;
use Resta\Support\Utils;
use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\Foundation\PathManager\StaticPathModel;

class Provider extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='provider';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'    => 'Creates a provider',
    ];

    /**
     * @var bool
     */
    protected $projectStatus = true;

    /**
     * @var array
     */
    protected $commandRule=[];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $bootName=explode("\\",$this->argument['project']);

        if(file_exists(app()->path()->app())){

            if(!isset($this->argument['provider'])) exception()->invalidArgument('Provider key is not valid');

            $this->argument['ProviderNamespace'] = app()->namespace()->provider();
            $this->touch['provider/provider'] = app()->path()->provider().'/'.$this->argument['provider'].'.php';
        }
        else{

            $providerName = explode(":",current($bootName));

            $this->argument['provider']=ucfirst(end($providerName));
            $this->argument['ProviderNamespace'] = 'Providers';
            $this->touch['provider/provider'] = StaticPathModel::providerDir().'/'.$this->argument['provider'].'.php';
        }

        $this->file->touch($this);

        echo $this->classical('Provider Class Named ['.$this->argument['provider'].'] Has Been Successfully Created');
    }
}