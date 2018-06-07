<?php

namespace Resta\Console\Source\Repository;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\StaticPathModel;
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
     * @var $commandRule
     */
    public $commandRule=['repository','?source'];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        if($this->sourceCreate()) return true;

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

        //set annotations
        $this->setAnnotations();

        echo $this->info('Repository Named "'.$this->argument['repository'].'" Has Been Successfully Created');

    }

    /**
     * @return bool
     */
    private function sourceCreate(){

        if(isset($this->argument['source'])){

            $this->directory['repositorySourceCustomDir']=$this->repository().'/'.$this->argument['repository'].'/Source/'.$this->argument['source'];

            //set project directory
            $this->file->makeDirectory($this);

            $this->touch['repository/sourcecustommain']=$this->directory['repositorySourceCustomDir'].'/'.$this->argument['repository'].''.$this->argument['source'].'.php';

            //set project touch
            $this->file->touch($this);

            Utils::chmod($this->repository());

            echo $this->info('Repository Named "'.$this->argument['source'].'" Has Been Successfully Created');

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    private function setAnnotations(){

        return Utils::changeClass(StaticPathModel::appAnnotation($this->projectName(),true).'',
            ['Trait ServiceAnnotationsController'=>'Trait ServiceAnnotationsController'.PHP_EOL.' * @method \\'.StaticPathModel::appRepository($this->projectName()).'\\'.$this->argument['repository'].'\\'.$this->argument['repository'].'Contract '.strtolower($this->argument['repository']).'Repository'
            ]);
    }
}