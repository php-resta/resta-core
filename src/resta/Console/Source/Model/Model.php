<?php

namespace Resta\Console\Source\Model;

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

        $this->argument['file']=$this->argument['model'];

        if(!isset($this->argument['table'])){
            $this->argument['table']=$this->argument['file'].'s';
        }

        //lower case for table
        $this->argument['table']=strtolower($this->argument['table']);

        $this->directory['modelDir']=$this->version().'/Model';
        $this->directory['builderDir']=$this->directory['modelDir'].'/Builder';
        $this->directory['contract']=$this->directory['modelDir'].'/Contract';
        $this->directory['helper']=$this->directory['modelDir'].'/Helper';

        //set project directory
        $this->file->makeDirectory($this);

        //model set
        $this->touch['model/model']     = $this->model().'/'.$this->argument['file'].'.php';
        $this->touch['model/builder']   = $this->model().'/Builder/'.$this->argument['file'].'Builder.php';
        $this->touch['model/contract']  = $this->model().'/Contract/'.$this->argument['file'].'Contract.php';

        if(!file_exists($this->directory['helper'].''.DIRECTORY_SEPARATOR.'Scope.php')){
            $this->touch['model/scope']     = $this->directory['helper'].''.DIRECTORY_SEPARATOR.'Scope.php';
        }

        //set project touch
        $this->file->touch($this,[
            'stub'=>'Model_Create'
        ]);

        $this->setAnnotations();

        echo $this->classical(' > Model called as "'.$this->argument['file'].'" has been successfully created in the '.app()->namespace()->model().'');
    }

    /**
     * @return bool
     */
    private function setAnnotations(){

        return Utils::changeClass(path()->serviceAnnotations().'.php',
            ['Trait ServiceAnnotationsManager'=>'Trait ServiceAnnotationsManager'.PHP_EOL.' * @method \\'.app()->namespace()->builder().'\\'.$this->argument['file'].'Builder '.strtolower($this->argument['file']).'Builder'
            ]);
    }
}