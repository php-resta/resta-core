<?php

namespace Resta\Console\Source\Token;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\StaticPathModel;
use Resta\Utils;

class Token extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='token';

    /**
     * @var $define
     */
    public $define='Token Generator';

    /**
     * @var $command_create
     */
    public $command_create='php api token create [projectName] key:[clientKey]';

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        //
        $clientApiToken=StaticPathModel::appMiddlewarePath($this->projectName()).'\\ClientApiToken';
        $resolveClientApiToken=Utils::makeBind($clientApiToken,$this->app->app->applicationProviderBinding($this->app->app));

        //
        $key=lcfirst($this->argument['key']);

        return $this->info($resolveClientApiToken->createToken($key));
    }
}