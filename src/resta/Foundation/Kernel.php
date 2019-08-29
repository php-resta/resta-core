<?php

namespace Resta\Foundation;

use Resta\Container\Container;

abstract class Kernel extends Container
{
    /**
     * get origin groups
     *
     * @var array
     */
    protected $originGroups=[
        'url'               => 'url',
        'route'             => 'route',
        'environment'       => 'environment',
        'logger'            => 'logger',
        'config'            => 'config',
        'encrypter'         => 'encrypter',
        'eventDispatcher'   => 'eventDispatcher',
        'serviceProvider'   => 'serviceProvider',
    ];

    /**
     * get console groups
     *
     * @var array
     */
    protected $consoleGroups = [
        'console' => 'console',
    ];

    /**
     * get middleware groups
     *
     * @var array
     */
    protected $middlewareGroups=[
        'middleware' => 'middleware'
    ];

    /**
     * get reflection groups
     *
     * @var array
     */
    protected $reflectionGroups=[
        'router'    => 'router',
        'response'  => 'response',
    ];

    /**
     * get command list
     *
     * @var array
     */
    protected $commandList = [

        'Resta\Console\Source\Autoservice\Autoservice'      => ['isRunnable' => true],
        'Resta\Console\Source\Boot\Boot'                    => ['isRunnable' => true],
        'Resta\Console\Source\Provider\Provider'            => ['isRunnable' => true],
        'Resta\Console\Source\Command\Command'              => ['isRunnable' => true],
        'Resta\Console\Source\Controller\Controller'        => ['isRunnable' => true],
        'Resta\Console\Source\Env\Env'                      => ['isRunnable' => true],
        'Resta\Console\Source\Event\Event'                  => ['isRunnable' => true],
        'Resta\Console\Source\Exception\Exception'          => ['isRunnable' => true],
        'Resta\Console\Source\Key\Key'                      => ['isRunnable' => true],
        'Resta\Console\Source\Middleware\Middleware'        => ['isRunnable' => true],
        'Resta\Console\Source\Migration\Migration'          => ['isRunnable' => true],
        'Resta\Console\Source\Model\Model'                  => ['isRunnable' => true],
        'Resta\Console\Source\Project\Project'              => ['isRunnable' => true],
        'Resta\Console\Source\Repository\Repository'        => ['isRunnable' => true],
        'Resta\Console\Source\Request\Request'              => ['isRunnable' => true],
        'Resta\Console\Source\Client\Client'                => ['isRunnable' => true],
        'Resta\Console\Source\Token\Token'                  => ['isRunnable' => true],
        'Resta\Console\Source\Factory\Factory'              => ['isRunnable' => true],
        'Resta\Console\Source\Route\Route'                  => ['isRunnable' => true],
        'Resta\Console\Source\Helper\Helper'                => ['isRunnable' => true],
        'Resta\Console\Source\Test\Test'                    => ['isRunnable' => true],
        'Resta\Console\Source\Worker\Worker'                => ['isRunnable' => true],
        'Resta\Console\Source\Path\Path'                    => ['isRunnable' => true],
        'Resta\Console\Source\Track\Track'                  => ['isRunnable' => true],
        'Resta\Console\Source\Schedule\Schedule'            => ['isRunnable' => true],
    ];

    /**
     * get service providers
     *
     * @var array $providers
     */
    protected $providers = [];
}