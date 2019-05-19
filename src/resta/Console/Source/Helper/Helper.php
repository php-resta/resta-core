<?php

namespace Resta\Console\Source\Helper;

use Resta\Support\Utils;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Resta\Foundation\PathManager\StaticPathModel;

class Helper extends ConsoleOutputter
{

    use ConsoleListAccessor;

    /**
     * @var string
     */
    public $type = 'helper';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates a helper file'
    ];

    /**
     * @var bool
     */
    protected $projectStatus = true;

    /**
     * @var array
     */
    public $commandRule=['helper'];

    /**
     * @return mixed
     */
    public function create()
    {

        if(!file_exists(app()->path()->helpers())){
            $this->directory['helper']          = app()->path()->helpers();
            $this->file->makeDirectory($this);
        }

        $this->touch['helpers/general']= app()->path()->helpers().'/'.ucfirst($this->argument['helper']).'.php';


        $this->file->touch($this);

        echo $this->classical(' > Helper called as "'.$this->argument['helper'].'" has been successfully created in the '.app()->namespace()->helpers().'');

    }
}