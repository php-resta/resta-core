<?php

namespace Resta\Foundation;

use Resta\Container\Container;

class Kernel extends Container
{
    /**
     * origin groups for kernel boot
     *
     * @var $boot
     */
    protected $boot=false;

    /**
     * @var array
     */
    protected $originGroups=[
        'url'               => 'urlProvider',
        'environment'       => 'environment',
        'logger'            => 'logger',
        'config'            => 'configProvider',
        'encrypter'         => 'encrypter',
        'eventDispatcher'   => 'eventDispatcher',
        'serviceProvider'   => 'serviceProvider',
    ];

    /**
     * 
     *
     * @var array
     */
    protected $consoleGroups = [
        'console' => 'appConsole',
    ];

    /**
     * @var array
     */
    protected $middlewareGroups=[
        'middleware' => 'middleware'
    ];
    /**
     * @var array
     */
    protected $reflectionGroups=[
        'router'    => 'router',
        'response'  => 'responseManager',
    ];

    /**
     * @var array
     */
    protected $commandList = [

        'Resta\Console\Source\Autoservice\Autoservice'      => ['isRunnable' => true],
        'Resta\Console\Source\Boot\Boot'                    => ['isRunnable' => true],
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
        'Resta\Console\Source\Token\Token'                  => ['isRunnable' => true],
        'Resta\Console\Source\Factory\Factory'              => ['isRunnable' => true],
    ];
}