<?php

namespace Resta\Console\Source\Controller;

use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Resta\Foundation\PathManager\StaticPathList;
use Resta\Foundation\PathManager\StaticPathModel;

class Controller extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='controller';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'    => 'Creates a controller',
        'all'       => 'Lists all controller'
    ];

    /**
     * @var bool
     */
    protected $projectStatus = true;

    /**
     * @var array
     */
    protected $commandRule=[
        'create'    => ['controller'],
        'all'       => [],
    ];

    /**
     * @method create
     * @return mixed|void
     */
    public function create()
    {
        $controller                     = $this->argument['controller'];
        $configurationInController      = $this->argument['configurationInController'] = StaticPathList::$configurationInController;

        if(isset($this->argument['module'])){
            $appControllerPath = app()->path()->controller().''.DIRECTORY_SEPARATOR.''.$this->argument['module'];
            $appControllerNamespace = app()->namespace()->controller().'\\'.$this->argument['module'];

            $module = $this->argument['module'];
        }
        else{
            $appControllerPath = app()->path()->controller();
            $appControllerNamespace = app()->namespace()->controller();

            $module = null;
        }

        $this->argument['bundleName'] = $controller.''.StaticPathList::$controllerBundleName;

        if(!file_exists($appControllerPath)){
            $this->directory['createController'] = $appControllerPath;
        }

        //Processes related to argument variables via console.
        $this->argument['methodPrefix']         = StaticPathModel::$methodPrefix;
        $this->directory['endpoint']            = $appControllerPath.''.DIRECTORY_SEPARATOR.''.$controller.''.StaticPathList::$controllerBundleName;
        $this->directory['configuration']       = $this->directory['endpoint'].'/'.$configurationInController;


        $this->argument['controllerNamespace']  = $appControllerNamespace.'\\'.$controller.''.StaticPathList::$controllerBundleName;
        $this->argument['serviceClass']         = $controller;
        $this->argument['callClassPrefix']      = StaticPathModel::$callClassPrefix;

        if(!is_null($module)){

            $this->directory['routes']       = path()->route().''.DIRECTORY_SEPARATOR.''.$module;
            $routePath = $this->directory['routes'].''.DIRECTORY_SEPARATOR.''.$controller.'Route.php';
        }
        else{

            $this->directory['routes']       = path()->route();
            $routePath = $this->directory['routes'].''.DIRECTORY_SEPARATOR.''.$controller.'Route.php';
        }

        // with the directory operation,
        // we get to the service directory, which is called the controller.
        $this->file->makeDirectory($this);

        if(isset($this->argument['file']) && file_exists($this->directory['endpoint'])){

            $fileNamespace = (!is_null($module)) ? $module.'\\'.$controller : $controller;

            if(isset($this->argument['type']) && $this->argument['type']=='Crud'){

                $this->touch['service/controllerfilecrud'] = $this->directory['endpoint'].''.DIRECTORY_SEPARATOR.''.$this->argument['file'].''. $this->argument['callClassPrefix'].'.php';

                $this->file->touch($this,[
                    'stub'=>'Controller_Create'
                ]);

                files()->append($routePath,PHP_EOL.'Route::namespace(\''.$fileNamespace.'\')->get("/'.strtolower($this->argument['file']).'","'.$this->argument['file'].'Controller@get");');
                files()->append($routePath,PHP_EOL.'Route::namespace(\''.$fileNamespace.'\')->post("/'.strtolower($this->argument['file']).'","'.$this->argument['file'].'Controller@create");');
                files()->append($routePath,PHP_EOL.'Route::namespace(\''.$fileNamespace.'\')->put("/'.strtolower($this->argument['file']).'","'.$this->argument['file'].'Controller@update");');

            }
            else{

                $this->touch['service/controllerfile']   = $this->directory['endpoint'].''.DIRECTORY_SEPARATOR.''.$this->argument['file'].''. $this->argument['callClassPrefix'].'.php';

                $this->file->touch($this,[
                    'stub'=>'Controller_Create'
                ]);

                files()->append($routePath,PHP_EOL.'Route::namespace(\''.$fileNamespace.'\')->get("/'.strtolower($this->argument['file']).'","'.$this->argument['file'].'Controller@index");');
            }

            // and as a result we print the result on the console screen.
            echo $this->classical(' > Controller called as "'.$this->argument['file'].'" has been successfully created in the '.$this->directory['endpoint'].'');

        }
        else{

            if(isset($this->argument['type']) && $this->argument['type']=='Crud'){

                $this->argument['file'] = $this->argument['serviceClass'];

                $this->touch['service/controllerfilecrud']  = $this->directory['endpoint'].''.DIRECTORY_SEPARATOR.''.$this->argument['serviceClass'].''. $this->argument['callClassPrefix'].'.php';
                $this->touch['service/routecrud']           = $routePath;

            }
            else{
                $this->touch['service/endpoint']        = $this->directory['endpoint'].''.DIRECTORY_SEPARATOR.''.$this->argument['serviceClass'].''. $this->argument['callClassPrefix'].'.php';

                if(!is_null($module)){
                    $this->touch['service/routeModule']           = $routePath;
                }
                else{
                    $this->touch['service/route']           = $routePath;
                }
            }

            $this->touch['service/app']             = $this->directory['endpoint'].''.DIRECTORY_SEPARATOR.'App.php';
            $this->touch['service/developer']       = $this->directory['configuration'].''.DIRECTORY_SEPARATOR.'Developer.php';
            $this->touch['service/conf']            = $this->directory['configuration'].''.DIRECTORY_SEPARATOR.'ServiceConf.php';
            $this->touch['service/dummy']           = $this->directory['configuration'].''.DIRECTORY_SEPARATOR.'Dummy.yaml';
            $this->touch['service/doc']             = $this->directory['configuration'].''.DIRECTORY_SEPARATOR.'Doc.yaml';
            $this->touch['service/readme']          = $this->directory['endpoint'].''.DIRECTORY_SEPARATOR.'README.md';

            $this->file->touch($this,[
                'stub'=>'Controller_Create'
            ]);

            // and as a result we print the result on the console screen.
            echo $this->classical(' > Controller called as "'.$controller.'" has been successfully created in the '.app()->namespace()->controller().'');
        }
    }
}