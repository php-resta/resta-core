<?php

namespace Resta\Console\Source\Token;

use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Resta\Container\DIContainerManager;
use Resta\Foundation\PathManager\StaticPathModel;

class Token extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='token';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates application token'
    ];

    /**
     * @var bool
     */
    protected $projectStatus = true;

    /**
     * @var $commandRule
     */
    public $commandRule=['key'];

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        //
        $clientApiToken=StaticPathModel::appMiddlewarePath($this->projectName()).'\\ClientApiToken';
        $resolveClientApiToken=DIContainerManager::resolve($clientApiToken,$this->app->app->applicationProviderBinding($this->app->app));

        //
        $key=lcfirst($this->argument['key']);

        echo $this->info($resolveClientApiToken->createToken($key));
    }
}