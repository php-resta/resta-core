<?php

namespace Resta\Console\Source\Model;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\StaticPathModel;
use Resta\Utils;

class Model extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='model';

    /**
     * @var $define
     */
    public $define='Model creating';

    /**
     * @var $commandRule
     */
    public $commandRule=['file','table'];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

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
        $this->file->touch($this);

        $this->setAnnotations();

        Utils::chmod($this->model());

        echo $this->info('Model Has Been Succesfully Created');
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