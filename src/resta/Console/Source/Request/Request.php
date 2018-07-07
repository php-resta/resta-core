<?php

namespace Resta\Console\Source\Request;

use const Grpc\STATUS_ABORTED;
use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\StaticPathModel;
use Resta\Utils;

class Request extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='request';

    /**
     * @var $define
     */
    public $define='Request create';

    /**
     * @var $commandRule
     */
    public $commandRule=['request'];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $request=$this->argument['request'];



        $this->directory['requestDir']=$this->optional().'/'.StaticPathModel::$sourcePath.'/'.StaticPathModel::$sourceRequest;

        //set project directory
        $this->file->makeDirectory($this);

        if(!file_exists($this->sourceRequestDir().'/Request.php')){
            $this->touch['source/request']              = $this->sourceRequestDir().'/Request.php';
        }


        $this->touch['source/requestFile']        =$this->directory['requestDir'].'/'.$request.'Request.php';

        //set project touch
        $this->file->touch($this);

        Utils::chmod($this->optional());

        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->bluePrint('Request Named ['.$request.'] Has Been Successfully Created');
        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->cyan('   You can see in repository directory your '.$this->directory['requestDir'].'   ');
        echo $this->classical('---------------------------------------------------------------------------');
    }
}