<?php

namespace Resta\Console\Source\Route;

use Resta\Foundation\PathManager\StaticPathList;
use Resta\Router\Route as Router;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Resta\Support\ReflectionProcess;
use Resta\Support\Utils;

class Route extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='route';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'list'=>'lists all routes for project'
    ];

    /**
     * @var bool
     */
    protected $projectStatus = true;

    /**
     * @var $commandRule
     */
    public $commandRule=[];

    /**
     * @return bool
     */
    public function list(){

        echo $this->info('All Route Controller Lists :');

        $this->table->setHeaders(['endpoint','http','namespace','method','definition','middleware','event','doc','status']);

        $routes = Router::getRoutes();
        $routeData = isset($routes['data']) ? $routes['data'] : [];
        $routePattern = isset($routes['pattern']) ? $routes['pattern'] : [];

        foreach($routeData as $key=>$data){

            $endpoint = strtolower(str_replace(StaticPathList::$controller,'',$data['class']));
            $controllerNamespace = Utils::getNamespace($data['controller'].'/'.$data['namespace'].'/'.$data['class']);

            $methodDocument = app()['reflection']($controllerNamespace)->reflectionMethodParams($data['method'])->document;

            if(preg_match('@#define:(.*?)\r\n@is',$methodDocument,$definition)){
               $definition = rtrim($definition[1]);
            }

            $this->table->addRow([
                $endpoint.'/'.implode("/",$routePattern[$key]),
                $data['http'],
                $controllerNamespace,
                $data['method'],
                $definition,
                '',
                '',
                '',
                ''
            ]);
        }


        echo $this->table->getTable();

    }
}