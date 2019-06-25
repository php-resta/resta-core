<?php

namespace Resta\Worker;

use Resta\Support\Utils;
use Resta\Foundation\ApplicationProvider;
use Resta\Contracts\ApplicationContracts;

abstract class WorkerManagerAbstract extends ApplicationProvider
{
    /**
     * @var array
     */
    protected $args = array();

    /**
     * @var null|object
     */
    protected $worker;

    /**
     * WorkerManager constructor.
     *
     * @param ApplicationContracts $app
     * @param array $args
     */
    public function __construct(ApplicationContracts $app, $args=array())
    {
        parent::__construct($app);

        if($this->app->runningInConsole()===false){
            exception()->runtime('The worker can only be run as cli..');
        }

        if($this->app->has('WORKER')===false){
            exception()->runtime('The worker console manipulation for WORKER container value');
        }

        $this->args = $args;

        $this->worker = $this->app->get('worker');
    }

    /**
     * get apply from cli for worker
     *
     * @return mixed
     */
    public function getApply()
    {
        if(isset($this->args['apply'])){
            return $this->args['apply'];
        }

        return 'supervisor';
    }

    /**
     * get data from cli for worker
     *
     * @return mixed
     */
    public function getData()
    {
        if(isset($this->args['data'])){
            return $this->args['data'];
        }

        return null;
    }

    /**
     * get pause value
     *
     * @return int|mixed
     */
    public function getPauseValue()
    {
        if(isset($this->args['pause'])){
            return $this->args['pause'];
        }

        return 0;
    }

    /**
     * get worker name
     *
     * @return mixed|void
     */
    public function getWorker()
    {
        if(isset($this->args['worker'],$this->worker[$worker = strtolower($this->args['worker'])])){
            return $worker;
        }

        exception()->runtime('Any worker is not available in the container');
    }

    /**
     * check if worker is available
     *
     * @return bool
     */
    public function isWorkerAvailable()
    {
        return isset($this->worker[$this->getWorker()]);
    }

    /**
     * get pause for worker
     *
     * @param null|\int $pause
     */
    public function pause($pause=null)
    {
        if(is_null($pause)){
            sleep($this->getPauseValue());
        }
        else{
            sleep($pause);
        }
    }

    /**
     * run job class
     *
     * @param $name
     * @param $arguments
     * @return mixed|void
     */
    public function __call($name, $arguments)
    {
        $job = $this->app->get('macro')->call($this->getApply().'Worker',function() use($name){
           return __NAMESPACE__.'\\'.ucfirst($name).'Job';
        });

        if(Utils::isNamespaceExists($job)){
            return $this->app->resolve($job,['worker'=>$this])->execute();
        }

        exception()->runtime('Job Class not found');
    }
}