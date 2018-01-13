<?php

namespace Resta\Console\Source\Model;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;

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
     * @var $command_create
     */
    public $command_create='php api model create [project] file:[file] table:[table]';

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        //lower case for table
        $this->argument['table']=strtolower($this->argument['table']);

        //model set
        $this->touch['model/model']= $this->model().'/'.$this->argument['file'].'.php';

        //set project touch
        $this->file->touch($this);

        return $this->blue('Model Has Been Succesfully Created');
    }
}