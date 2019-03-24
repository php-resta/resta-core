<?php

namespace Resta\Console\Source\Request;

use Resta\Support\Utils;
use const Grpc\STATUS_ABORTED;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Resta\Foundation\PathManager\StaticPathModel;

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
     * @var bool
     */
    protected $projectStatus = true;

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


        $this->directory['requestCreate']   = path()->request();
        $this->argument['requestNamespace'] = Utils::getNamespace($this->directory['requestCreate']);
        $this->directory['requestDir']      = $this->directory['requestCreate'].'/'.$request;

        //set project directory
        $this->file->makeDirectory($this);

        if(!file_exists($this->directory['requestCreate'].'/Request.php')){
            $this->touch['source/request'] = $this->directory['requestCreate'].'/Request.php';
            $this->touch['source/requestGenerator'] = $this->directory['requestCreate'].'/RequestGenerator.php';
        }

        if(!file_exists($this->directory['requestCreate'].'/RequestProvider.php')){
            $this->touch['source/requestProvider'] = $this->directory['requestCreate'].'/RequestProvider.php';
        }


        $this->touch['source/requestFile'] = $this->directory['requestDir'].'/'.$request.'Request.php';
        $this->touch['source/requestGeneratorFile'] = $this->directory['requestDir'].'/'.$request.'RequestGenerator.php';

        //set project touch
        $this->file->touch($this);
        echo $this->classical(' > Request called as "'.$request.'" has been successfully created in the '.app()->namespace()->optionalSource().'');
    }
}