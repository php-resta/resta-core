<?php

namespace Resta\Console\Source\Source;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\StaticPathModel;
use Resta\Utils;

class Source extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='source';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates source file'
    ];

    /**
     * @var $commandRule
     */
    public $commandRule=['service','source','?file'];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $this->argument['sourcePath']           = StaticPathModel::$sourcePath;
        $this->argument['sourceDir']            = $this->argument['source'].'Source';
        $this->directory['sourcePath']          = $this->sourceEndpointDir().'/'.$this->argument['service'];
        $this->directory['sourceMainPath']      = $this->directory['sourcePath'].'/'.$this->argument['sourceDir'];

        if(!isset($this->argument['file'])){

            $this->file->makeDirectory($this);
            $this->argument['sourceFile'] = 'Main';
            $this->touch['source/main']   = $this->directory['sourceMainPath'].'/Main.php';
        }
        else{

            if(!file_exists($this->directory['sourceMainPath'].'/Main.php')){
                throw new \LogicException('Main.php is not available');
            }

            $this->argument['sourceFile'] = $this->argument['file'];
            $this->touch['source/sourcefile']   = $this->directory['sourceMainPath'].'/'.$this->argument['file'].'.php';
        }


        if(!file_exists($this->directory['sourcePath'].'/Interface'.$this->argument['sourcePath'].'.php')){
            $this->touch['source/interface']        = $this->directory['sourcePath'].'/Interface'.$this->argument['sourcePath'].'.php';

        }

        $this->file->touch($this);

        if(!isset($this->argument['file'])){
            $this->setAnnotations();
        }


        Utils::chmod($this->optional());

        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->bluePrint('Source For Endpoint Named ['.$this->argument['source'].'] Has Been Successfully Created in the '.$this->argument['project'].'');
        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->cyan('   You can see in src/app/'.$this->argument['project'].'/'.Utils::getAppVersion($this->argument['project']).'/__Call your project   ');
        echo $this->classical('---------------------------------------------------------------------------');
    }

    /**
     * @return bool
     */
    private function setAnnotations(){

        return Utils::changeClass(StaticPathModel::appAnnotation($this->projectName(),true).'',
            ['Trait ServiceAnnotationsController'=>'Trait ServiceAnnotationsController'.PHP_EOL.' * @method \\'.Utils::getNamespace($this->directory['sourceMainPath'].'/Main').' '.strtolower($this->argument['service']).''.$this->argument['source'].''.$this->argument['sourcePath']
            ]);
    }
}