<?php

namespace Resta\Console\Source\Repository;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\Utils;

class Repository extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='repository';

    /**
     * @var $define
     */
    public $define='repository';

    /**
     * @var $command_create
     */
    public $command_create='php api repository create [project] repository:[repository] source?:[source]';

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $repository=$this->argument['repository'];

        $this->directory['repositoryDir']=$this->repository().'/'.$repository;
        $this->directory['repositorySourceDir']=$this->repository().'/'.$repository.'/Source/Main';

        //set project directory
        $this->file->makeDirectory($this);

        $this->touch['repository/adapter']      =$this->directory['repositoryDir'].'/'.$repository.'Adapter.php';
        $this->touch['repository/contract']     =$this->directory['repositoryDir'].'/'.$repository.'Contract.php';
        $this->touch['repository/trait']        =$this->directory['repositoryDir'].'/'.$repository.'Trait.php';

        $this->touch['repository/sourcemain']       =$this->directory['repositorySourceDir'].'/'.$repository.'Main.php';

        //set project touch
        $this->file->touch($this);

        Utils::chmod($this->repository());

        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->bluePrint('Repository Named ['.$this->argument['repository'].'] Has Been Successfully Created');
        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->cyan('   You can see in repository directory your repository   ');
        echo $this->classical('---------------------------------------------------------------------------');
    }
}