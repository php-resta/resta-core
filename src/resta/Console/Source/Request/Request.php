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
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates request object'
    ];

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

        $this->directory['requestDir']=$this->optional().'/'.StaticPathModel::$sourcePath.'/'.StaticPathModel::$sourceRequest.'/'.$request;

        //set project directory
        $this->file->makeDirectory($this);

        if(!file_exists($this->sourceRequestDir().'/Request.php')){
            $this->touch['source/request']              = $this->sourceRequestDir().'/Request.php';
            $this->touch['source/requestGenerator']              = $this->sourceRequestDir().'/RequestGenerator.php';
        }

        if(!file_exists($this->sourceRequestDir().'/RequestProvider.php')){
            $this->touch['source/requestProvider']      = $this->sourceRequestDir().'/RequestProvider.php';
        }


        $this->touch['source/requestFile']              = $this->directory['requestDir'].'/'.$request.'Request.php';
        $this->touch['source/requestGeneratorFile']         = $this->directory['requestDir'].'/'.$request.'RequestGenerator.php';

        //set project touch
        $this->file->touch($this);

        Utils::chmod($this->optional());

        echo $this->classical(' > Request called as "'.$request.'" has been successfully created in the '.app()->namespace()->optionalSource().'');
    }
}