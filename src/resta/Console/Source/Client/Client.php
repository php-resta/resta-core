<?php

namespace Resta\Console\Source\Client;

use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Resta\Foundation\PathManager\StaticPathModel;
use Resta\Support\Utils;

class Client extends ConsoleOutputter 
{
    use ConsoleListAccessor;

    /**
     * @var string
     */
    public $type = 'client';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates request object'
    ];

    /**
     * @var bool
     */
    protected $projectStatus = true;

    /**
     * @var array
     */
    public $commandRule=['name','client'];

    /**
     * @method create
     * @return void|mixed
     */
    public function create()
    {
        $name = $this->argument['name'];
        $client = $this->argument['client'];


        $this->directory['clientNameCreate']        = path()->request();
        $this->argument['clientNameNamespace']      = Utils::getNamespace($this->directory['clientNameCreate']);
        $this->directory['clientNameDir']           = $this->directory['clientNameCreate'].'/'.$name;
        $this->directory['clientSource']            = $this->directory['clientNameDir'].'/'.$client;
        $this->argument['clientSourceNamespace']   = Utils::getNamespace($this->directory['clientNameDir'].'/'.$client.'');

        //set project directory
        $this->file->makeDirectory($this);

        if(!file_exists($this->directory['clientNameCreate'].'/Client.php')){
            $this->touch['client/client'] = $this->directory['clientNameCreate'].'/Client.php';
            $this->touch['client/clientGenerator'] = $this->directory['clientNameCreate'].'/ClientGenerator.php';
        }

        if(!file_exists($clientSourceName = $this->directory['clientSource'].'/'.$client.'.php')){
            $this->touch['client/source'] = $clientSourceName.'';
            //$this->touch['client/sourcegenerator'] = $this->directory['clientSource'].'/'.$client.'Generator.php';
        }

        if(!file_exists($this->directory['clientNameCreate'].'/ClientProvider.php')){
            $this->touch['client/clientProvider'] = $this->directory['clientNameCreate'].'/ClientProvider.php';
        }

        $this->touch['client/clientGeneratorFile'] = $this->directory['clientSource'].'/'.$client.'Generator.php';
        
        
        //set project touch
        $this->file->touch($this);

        echo $this->classical(' > Client called as "'.$client.'" has been successfully created in the '.app()->namespace()->request().'');
    }
}