<?php

namespace Resta\Console\Source\Repository;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\StaticPathModel;
use Resta\Support\Utils;

class Repository extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='repository';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates repository structure'
    ];

    /**
     * @var bool
     */
    protected $projectStatus = true;

    /**
     * @var $commandRule
     */
    public $commandRule=['repository','?source'];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $repository=$this->argument['repository'];

        $repositoryPath=$this->repository().'/'.$repository;

        $this->argument['repositoryNamespace']=Utils::getNamespace($repositoryPath);

        if($this->sourceCreate()) return true;

        $this->directory['repositoryPath'] = $this->repository();
        $this->directory['repositoryDir']=$repositoryPath;

        $this->directory['repositorySourceDir']=$this->repository().'/'.$repository.'/Source';
        $this->directory['repositorySourceMainDir']=$this->directory['repositorySourceDir'].'/Main';

        //set project directory
        $this->file->makeDirectory($this);

        $this->touch['repository/adapter']      =$this->directory['repositoryDir'].'/'.$repository.'Adapter.php';
        $this->touch['repository/contract']     =$this->directory['repositoryDir'].'/'.$repository.'Contract.php';
        $this->touch['repository/trait']        =$this->directory['repositoryDir'].'/'.$repository.'Trait.php';

        $this->touch['repository/sourcemain']       =$this->directory['repositorySourceMainDir'].'/'.$repository.'Main.php';

        //set project touch
        $this->file->touch($this,[
            'stub'=>'Repository_Create'
        ]);
        
        //set annotations
        $this->setAnnotations();

        echo $this->classical(' > Repository called as "'.$this->argument['repository'].'" has been successfully created in the '.app()->namespace()->repository().'');

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

        return Utils::changeClass(app()->path()->kernel().'/AppAnnotations.php',
            ['Trait ServiceAnnotationsController'=>'Trait ServiceAnnotationsController'.PHP_EOL.' * @method \\'.app()->namespace()->repository().'\\'.$this->argument['repository'].'\\'.$this->argument['repository'].'Contract '.lcfirst($this->argument['repository']).'Repository'
            ]);
    }
}