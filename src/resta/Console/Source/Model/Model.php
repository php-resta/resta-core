<?php

namespace Resta\Console\Source\Model;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\StaticPathModel;
use Resta\Support\Utils;

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

        //set project directory
        $this->file->makeDirectory($this);

        //set project directory
        $this->file->makeDirectory($this);

        //model set
        $this->touch['model/model']     = $this->model().'/'.$this->argument['file'].'.php';
        $this->touch['model/builder']   = $this->model().'/Builder/'.$this->argument['file'].'Builder.php';

        //set project touch
        $this->file->touch($this,[
            'stub'=>'Model_Create'
        ]);

        $this->setAnnotations();

        Utils::chmod($this->model());

        echo $this->classical(' > Model called as "'.$this->argument['file'].'" has been successfully created in the '.app()->namespace()->model().'');
    }

    /**
     * @return bool
     */
    private function setAnnotations(){

        return Utils::changeClass(app()->path()->serviceAnnotations().'.php',
            ['Trait ServiceAnnotationsController'=>'Trait ServiceAnnotationsController'.PHP_EOL.' * @method \\'.app()->namespace()->builder().'\\'.$this->argument['file'].'Builder '.strtolower($this->argument['file']).'Builder'
            ]);
    }
}