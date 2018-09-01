<?php

namespace Resta\Console\Source\Service;

use Resta\Utils;
use Resta\StaticPathModel;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;

class Service extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='service';

    /**
     * @var $define
     */
    public $define='Service create';

    /**
     * @var array
     */
    protected $commandRule=['service','?namespace'];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        //Processes related to argument variables via console.
        $this->argument['methodPrefix']         = StaticPathModel::$methodPrefix;
        $this->directory['endpoint']            = $this->controller().'/'.$this->argument['service'];
        $this->argument['controllerNamespace']  = Utils::getNamespace($this->directory['endpoint']);
        $this->argument['serviceClass']         = $this->argument['service'];

        // with the directory operation,
        // we get to the service directory, which is called the controller.
        $this->directory['endpoint']            = $this->controller().'/'.$this->argument['service'];
        $this->file->makeDirectory($this);

        // we process the processes related to file creation operations.
        // and then create files related to the touch method of the file object as it is in the directory process.
        $this->touch['service/endpoint']        = $this->directory['endpoint'].'/'.$this->argument['serviceClass'].'Service.php';
        $this->touch['service/acl']             = $this->directory['endpoint'].'/'.$this->argument['serviceClass'].'AclManagement.php';
        $this->touch['service/app']             = $this->directory['endpoint'].'/App.php';
        $this->touch['service/developer']       = $this->directory['endpoint'].'/Developer.php';
        $this->touch['service/conf']            = $this->directory['endpoint'].'/ServiceConf.php';
        $this->touch['service/dummy']           = $this->directory['endpoint'].'/Dummy.yaml';

        $this->file->touch($this,[
            'stub'=>'Service_Create'
        ]);

        // after all the operations, we apply chmod to the controller directory.
        Utils::chmod($this->controller());

        // and as a result we print the result on the console screen.
        echo $this->classical(' > Service called as "'.$this->argument['service'].'" has been successfully created in the '.app()->namespace()->call().'');

    }
}