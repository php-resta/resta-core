<?php

namespace Resta\Console\Source\Project;

use Resta\Console\ConsoleOutputter;
use Resta\FileProcess;
use Resta\StaticPathModel;

class Project extends ConsoleOutputter {

    /**
     * @var $argument
     */
    public  $argument;

    /**
     * @var $file
     */
    public $file;

    /**
     * @var $directory
     */
    public $directory=array();


    /**
     * Project constructor.
     * @param $argument
     */
    public function __construct($argument){

        parent::__construct();
        $this->argument=$argument;
        $this->file=new FileProcess();
    }

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        //get project directory all path
        $this->directory['projectDir']       = StaticPathModel::appPath().'/'.$this->argument['project'];
        $this->directory['kernelDir']        = $this->directory['projectDir'].'/Kernel';
        $this->directory['middleWareDir']    = $this->directory['kernelDir'].'/Middleware';
        $this->directory['boot']             = $this->directory['kernelDir'].'/Boot';
        $this->directory['storageDir']       = $this->directory['projectDir'].'/Storage';
        $this->directory['logDir']           = $this->directory['storageDir'].'/Logs';
        $this->directory['resourceDir']      = $this->directory['storageDir'].'/Resource';
        $this->directory['languageDir']      = $this->directory['storageDir'].'/Language';
        $this->directory['sessionDir']       = $this->directory['storageDir'].'/Session';
        $this->directory['versionDir']       = $this->directory['projectDir'].'/V1';
        $this->directory['callDir']          = $this->directory['versionDir'].'/__Call';
        $this->directory['modelDir']         = $this->directory['versionDir'].'/Model';
        $this->directory['migrationDir']     = $this->directory['versionDir'].'/Migration';
        $this->directory['configDir']        = $this->directory['versionDir'].'/Config';
        $this->directory['optionalDir']      = $this->directory['versionDir'].'/Optional';

        //set project directory
        $this->file->makeDirectory($this);

        return $this->blue('Project Has Been Successfully Created');
    }
}