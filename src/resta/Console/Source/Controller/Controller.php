<?php

namespace Resta\Console\Source\Controller;

use Resta\Router\Route;
use Resta\Support\Utils;
use Resta\Config\Config;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Resta\Foundation\PathManager\StaticPathList;
use Resta\Foundation\PathManager\StaticPathModel;
use Resta\Services\Controller\Controller as CallController;

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
        'rename'    => 'Renames controller',
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
        'rename'    => ['controller','rename'],
        'all'       => [],
    ];

    /**
     * @method create
     * @return mixed
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
        //$this->directory['resource']            = $this->directory['endpoint'].'/'.$resourceInController;
        //$this->directory['policy']              = $this->directory['endpoint'].'/Policy';
        $this->directory['configuration']       = $this->directory['endpoint'].'/'.$configurationInController;


        $this->argument['controllerNamespace']  = $appControllerNamespace.'\\'.$controller.''.StaticPathList::$controllerBundleName;
        $this->argument['serviceClass']         = $controller;
        $this->argument['callClassPrefix']      = StaticPathModel::$callClassPrefix;

        $fullNamespaceForController             = $this->argument['controllerNamespace'].'\\'.$this->argument['serviceClass'].''.$this->argument['callClassPrefix'];
        

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
                
                $this->touch['service/controllerfilecrud']   = $this->directory['endpoint'].''.DIRECTORY_SEPARATOR.''.$this->argument['file'].''. $this->argument['callClassPrefix'].'.php';

                $this->file->touch($this,[
                    'stub'=>'Controller_Create'
                ]);
                
                files()->append($routePath,PHP_EOL.'Route::namespace(\''.$fileNamespace.'\')->get("/'.strtolower($this->argument['file']).'","'.$this->argument['file'].'Controller@get");');
                files()->append($routePath,PHP_EOL.'Route::namespace(\''.$fileNamespace.'\')->post("/'.strtolower($this->argument['file']).'","'.$this->argument['file'].'Controller@create");');
                files()->append($routePath,PHP_EOL.'Route::namespace(\''.$fileNamespace.'\')->put("/'.strtolower($this->argument['file']).'","'.$this->argument['file'].'Controller@update");');
                //files()->append($routePath,PHP_EOL.'Route::namespace(\''.$controller.'\')->delete("/'.strtolower($this->argument['file']).'","'.$this->argument['file'].'Controller@delete");');

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

            // we process the processes related to file creation operations.
            // and then create files related to the touch method of the file object as it is in the directory process.
            
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
            
            //$this->touch['service/resourceIndex']   = $this->directory['resource'].''.DIRECTORY_SEPARATOR.'index.html';
            $this->touch['service/app']             = $this->directory['endpoint'].''.DIRECTORY_SEPARATOR.'App.php';
            $this->touch['service/developer']       = $this->directory['configuration'].''.DIRECTORY_SEPARATOR.'Developer.php';
            $this->touch['service/conf']            = $this->directory['configuration'].''.DIRECTORY_SEPARATOR.'ServiceConf.php';
            $this->touch['service/dummy']           = $this->directory['configuration'].''.DIRECTORY_SEPARATOR.'Dummy.yaml';
            $this->touch['service/doc']             = $this->directory['configuration'].''.DIRECTORY_SEPARATOR.'Doc.yaml';
            $this->touch['service/readme']          = $this->directory['endpoint'].''.DIRECTORY_SEPARATOR.'README.md';
            //$this->touch['service/policy']          = $this->directory['policy'].''.DIRECTORY_SEPARATOR.''.$this->argument['serviceClass'].''. $this->argument['callClassPrefix'].'Policy.php';

            $this->file->touch($this,[
                'stub'=>'Controller_Create'
            ]);

            $this->docUpdate();

            // and as a result we print the result on the console screen.
            echo $this->classical(' > Controller called as "'.$controller.'" has been successfully created in the '.app()->namespace()->call().'');


        }



    }

    /**
     * @return mixed
     *
     */
    public function rename()
    {
        $path = path()->controller().'/'.$this->argument['controller'].''.StaticPathList::$controllerBundleName;

        if(file_exists($path)){

            $newPathName = str_replace($this->argument['controller'],$this->argument['rename'],$path);


            rename($path,$newPathName);

            $getAllFiles = files()->getAllFilesInDirectory($newPathName);

            $getPathWithPhpExtensions = Utils::getPathWithPhpExtension($getAllFiles,$newPathName);

            foreach ($getPathWithPhpExtensions as $getPathWithPhpExtension){

                $withoutFullPath = str_replace($newPathName,"",$getPathWithPhpExtension);

                $newName = $newPathName."".preg_replace("((.*)".$this->argument['controller'].")", "$1".$this->argument['rename'], $withoutFullPath);

                rename($getPathWithPhpExtension,$newName);

                Utils::changeClass($newName,
                    [
                        $this->argument['controller']=>$this->argument['rename']
                    ]);

            }

            echo $this->classical($this->argument['controller'].' Controller has been changed as '.$this->argument['rename'].' Controller');

        }
    }

    /**
     * @return void
     */
    private function docUpdate()
    {
        $docPath = $this->directory['configuration'] .'/Doc.yaml';

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