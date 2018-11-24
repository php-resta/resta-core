<?php

namespace Resta\Console\Source\Controller;

use Resta\Utils;
use Resta\StaticPathModel;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;

class Controller extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='controller';

    /**
     * @var $define
     */
    public $define='Controller create';

    /**
     * @var array
     */
    protected $commandRule=['controller','?namespace'];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $controller = $this->argument['controller'];

        //Processes related to argument variables via console.
        $this->argument['methodPrefix']         = StaticPathModel::$methodPrefix;
        $this->directory['endpoint']            = $this->controller().'/'.$controller;
        $this->argument['controllerNamespace']  = Utils::getNamespace($this->directory['endpoint']);
        $this->argument['serviceClass']         = $controller;
        $this->argument['callClassPrefix']      = StaticPathModel::$callClassPrefix;

        // with the directory operation,
        // we get to the service directory, which is called the controller.
        $this->directory['endpoint']            = $this->controller().'/'.$controller;
        $this->file->makeDirectory($this);


        // we process the processes related to file creation operations.
        // and then create files related to the touch method of the file object as it is in the directory process.
        $this->touch['service/endpoint']        = $this->directory['endpoint'].'/'.$this->argument['serviceClass'].''. $this->argument['callClassPrefix'].'.php';
        $this->touch['service/acl']             = $this->directory['endpoint'].'/'.$this->argument['serviceClass'].'AclManagement.php';
        $this->touch['service/app']             = $this->directory['endpoint'].'/App.php';
        $this->touch['service/developer']       = $this->directory['endpoint'].'/Developer.php';
        $this->touch['service/conf']            = $this->directory['endpoint'].'/ServiceConf.php';
        $this->touch['service/dummy']           = $this->directory['endpoint'].'/Dummy.yaml';
        $this->touch['service/doc']             = $this->directory['endpoint'].'/Doc.yaml';
        $this->touch['service/readme']          = $this->directory['endpoint'].'/README.md';

        $this->file->touch($this,[
            'stub'=>'Service_Create'
        ]);

        $this->docUpdate();

        // after all the operations, we apply chmod to the controller directory.
        Utils::chmod($this->controller());
        
        // and as a result we print the result on the console screen.
        echo $this->classical(' > Controller called as "'.$controller.'" has been successfully created in the '.app()->namespace()->call().'');

    }

    /**
     * @return void
     */
    private function docUpdate()
    {
        $docPath = app()->path()->controller().'/'.$this->argument['serviceClass'].'/Doc.yaml';

        $doc = Utils::yaml($docPath);

        $data = [

            'index'=>[
                'http'=>'get',
                'params'=>[
                    'page'=>[
                        'required'=>false,
                        'type'=>'numeric'
                    ]
                ],
                'headers'=>[],
            ]
        ];

        $doc->set($data);
    }
}