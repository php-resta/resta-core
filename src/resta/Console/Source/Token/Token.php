<?php

namespace Resta\Console\Source\Token;

use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
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
     * @return void
     */
    public function create()
    {
        $clientApiToken = app()->namespace()->middleware().'\\ClientApiToken';
        $resolveClientApiToken = app()->resolve($clientApiToken);

        //
        $key= lcfirst($this->argument['key']);

        echo $this->info($resolveClientApiToken->createToken($key));
    }
}