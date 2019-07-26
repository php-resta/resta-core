<?php

namespace Resta\Console\Source\Model;

use Resta\Support\Generator\Generator;
use Resta\Support\Utils;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Resta\Foundation\PathManager\StaticPathModel;

class Model extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='model';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates a model file'
    ];

    /**
     * @var bool
     */
    protected $projectStatus = true;

    /**
     * @var $commandRule
     */
    public $commandRule=['model','?table'];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $this->argument['file'] = $this->argument['model'];

        if(!isset($this->argument['table'])){
            $this->argument['table'] = $this->argument['file'].'s';
        }

        //lower case for table
        $this->argument['table'] = strtolower($this->argument['table']);

        $this->directory['modelDir']    = app()->path()->model();
        $this->directory['builderDir']  = $this->directory['modelDir'].'/Builder';
        $this->directory['contract']    = $this->directory['modelDir'].'/Contract';
        $this->directory['helper']      = $this->directory['modelDir'].'/Helper';
        
        $this->argument['modelNamespace'] = Utils::getNamespace($this->directory['modelDir']);
        $this->argument['builderNamespace'] = Utils::getNamespace($this->directory['builderDir']);
        $this->argument['contractNamespace'] = Utils::getNamespace($this->directory['contract']);

        //set project directory
        $this->file->makeDirectory($this);

        //model set
        $this->touch['model/model']     = $this->directory['modelDir'].''.DIRECTORY_SEPARATOR.''.$this->argument['file'].'.php';
        $this->touch['model/builder']   = $this->directory['builderDir'].''.DIRECTORY_SEPARATOR.''.$this->argument['file'].'Builder.php';
        $this->touch['model/contract']  = $this->directory['contract'].''.DIRECTORY_SEPARATOR.''.$this->argument['file'].'Contract.php';

        if(!file_exists($this->directory['helper'].''.DIRECTORY_SEPARATOR.'Scope.php')){
            $this->touch['model/scope'] = $this->directory['helper'].''.DIRECTORY_SEPARATOR.'Scope.php';
        }

        $tableMaps = $this->directory['modelDir'].''.DIRECTORY_SEPARATOR.'Map.php';
        
        $generator = new Generator($this->directory['builderDir'],'BuilderMap');

        if(!file_exists($this->directory['builderDir'].''.DIRECTORY_SEPARATOR.'BuilderMap.php')){

            $this->setAnnotations();
            $generator->createClass();
        }
        
        if(!file_exists($this->touch['model/model'])){
            
            $generator->createMethod([
                strtolower($this->argument['file'])
            ]);
            
            $generator->createMethodBody([
                strtolower($this->argument['file'])=>'return new '.$this->argument['file'].'Builder();'
            ]);
            
            $generator->createMethodDocument([
                strtolower($this->argument['file']) => [
                    $this->argument['file'].' Builder Instance',
                    '',
                    '@return '.$this->argument['file'].'Builder'
                ]
            ]);
            
        }
        
        //set project touch
        $this->file->touch($this,[
            'stub'=>'Model_Create'
        ]);
        

        echo $this->classical(' > Model called as "'.$this->argument['file'].'" has been successfully created in the '.app()->namespace()->model().'');
    }

    /**
     * @return bool
     */
    private function setAnnotations(){

        return Utils::changeClass(path()->serviceAnnotations().'.php',
            ['Trait ServiceAnnotationsManager'=>'Trait ServiceAnnotationsManager'.PHP_EOL.' * @property \\'.app()->namespace()->builder().'\BuilderMap builder'
            ]);
    }
}