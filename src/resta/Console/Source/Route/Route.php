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

        $this->table->setHeaders(['no','endpoint','http','namespace','method','definition','beforeMiddleware','afterMiddleware','routeCache','doc','status']);

        $routes = Router::getRoutes();
        $routeData = isset($routes['data']) ? $routes['data'] : [];
        $routePattern = isset($routes['pattern']) ? $routes['pattern'] : [];

        $counter=0;

        $application = app();

        $application->loadIfNotExistBoot(['middleware']);
        $middlewareProvider = $application['middleware'];

        foreach($routeData as $key=>$data){

            $middlewareProvider->setKeyOdds('endpoint',$data['endpoint']);
            $middlewareProvider->setKeyOdds('method',$data['method']);
            $middlewareProvider->setKeyOdds('http',$data['http']);

            $middlewareProvider->handleMiddlewareProcess();
            $beforeMiddleware = $middlewareProvider->getShow();

            $middlewareProvider->handleMiddlewareProcess('after');
            $afterMiddleware = $middlewareProvider->getShow();

            $endpoint = $data['endpoint'];
            $controllerNamespace = Utils::getNamespace($data['controller'].'/'.$data['namespace'].'/'.$data['class']);

            /**
             * @var ReflectionProcess $reflection
             */
            $reflection = app()['reflection']($controllerNamespace);
            $methodDocument = $reflection->reflectionMethodParams($data['method'])->document;

            $isRouteCache = ($reflection->isAvailableMethodDocument($data['method'],'cache')) ?
                'available' : '';

            $methodDefinition = '';

            if(preg_match('@#define:(.*?)\n@is',$methodDocument,$definition)){
                if(isset($definition[1])){
                    $methodDefinition = rtrim($definition[1]);
                }
            }

            $endpointData = $endpoint.'/'.implode("/",$routePattern[$key]);

            if(isset($this->argument['filter'])){

                if(preg_match('@'.$this->argument['filter'].'@is',$endpointData)){

                    $this->table->addRow([
                        ++$counter,
                        $endpointData,
                        $data['http'],
                        $controllerNamespace,
                        $data['method'],
                        $methodDefinition,
                        implode(",",$beforeMiddleware),
                        implode(",",$afterMiddleware),
                        $isRouteCache,
                        '',
                        '',
                        true
                    ]);
                }
            }
            else{

                $this->table->addRow([
                    ++$counter,
                    $endpointData,
                    $data['http'],
                    $controllerNamespace,
                    $data['method'],
                    $methodDefinition,
                    implode(",",$beforeMiddleware),
                    implode(",",$afterMiddleware),
                    $isRouteCache,
                    '',
                    true
                ]);
            }


        }


        echo $this->table->getTable();

    }
}