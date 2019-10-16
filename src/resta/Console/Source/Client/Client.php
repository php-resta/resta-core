<?php

namespace Resta\Console\Source\Client;

use Resta\Support\Utils;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Resta\Foundation\PathManager\StaticPathModel;
use Resta\Support\Generator\Generator;

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
        $this->argument['clientNameDirNamespace']   = Utils::getNamespace($this->directory['clientNameCreate'].'/'.$name);
        $this->directory['clientSource']            = $this->directory['clientNameDir'].'/'.$client;
        $this->argument['clientSourceNamespace']    = Utils::getNamespace($this->directory['clientNameDir'].'/'.$client.'');
        
        //set project directory
        $this->file->makeDirectory($this);

        $this->argument['managerTraitNamespace'] = Utils::getNamespace($this->directory['clientNameDir'].'/'.$name.'Trait.php');

        if(!file_exists($manager = $this->directory['clientNameDir'].'/'.$name.'Manager.php')){
            $this->touch['client/manager'] = $manager;
            $this->touch['client/managertrait'] = $this->directory['clientNameDir'].'/'.$name.'Trait.php';
            
        }

        if(!file_exists($this->directory['clientNameCreate'].'/Client.php')){
            $this->touch['client/client'] = $this->directory['clientNameCreate'].'/Client.php';
            $this->touch['client/clientGenerator'] = $this->directory['clientNameCreate'].'/ClientGenerator.php';
        }

        $clientSourceNamespace = Utils::getNamespace($this->directory['clientSource'].'/'.$client.'.php');

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

        $nameManager = $name.'Manager';

        $nameGeneratorNamespace = Utils::getNamespace($managerPath = $this->directory['clientNameDir'].''.DIRECTORY_SEPARATOR.''.$nameManager.'.php');

        $generator = new Generator(path()->version(),'ClientManager');

        $clientManager = app()->namespace()->version().'\\ClientManager';

        $clientManagerResolve = new $clientManager;

        if(!method_exists($clientManagerResolve,strtolower($name))){

            $generator->createMethod([
                strtolower($name)
            ]);

            $generator->createClassUse([
                $nameGeneratorNamespace
            ]);

            $generator->createMethodBody([
                strtolower($name) => 'return new '.$nameManager.'();'
            ]);

            $generator->createMethodDocument([
                strtolower($name) => [
                    '@return '.$nameManager.''
                ]
            ]);

            $generator->createMethodAccessibleProperty([
                strtolower($name) => 'protected'
            ]);

            Utils::changeClass(path()->version().''.DIRECTORY_SEPARATOR.'ClientManager.php',
                [
                    'Class ClientManager'=>'Class ClientManager'.PHP_EOL.' * @property '.$nameManager.' '.strtolower($name),
                ]);

        }

        $nameGenerator = new Generator($this->directory['clientNameDir'],$name.'Manager');

        $nameGeneratorNamespaceResolve = new $nameGeneratorNamespace;

        if(!method_exists($nameGeneratorNamespaceResolve,strtolower($client))){

            $nameGenerator->createMethod([
                strtolower($client)
            ]);

            $nameGenerator->createMethodAccessibleProperty([
                strtolower($client) => 'protected'
            ]);

            $nameGenerator->createMethodBody([
                strtolower($client) => 'return new '.$client.'();'
            ]);

            $nameGenerator->createMethodDocument([
                strtolower($client) => [
                    '@return '.$client.'',
                    '',
                    '@throws ReflectionException'
                ]
            ]);

            $nameGenerator->createClassUse([
                $clientSourceNamespace
            ]);


            Utils::changeClass($managerPath,
                [
                    'Class '.$name.'Manager'=>'Class '.$name.'Manager'.PHP_EOL.' * @property '.$client.' '.strtolower($client),
                ]);
        }



        echo $this->classical(' > Client called as "'.$client.'" has been successfully created in the '.app()->namespace()->request().'');
    }
}