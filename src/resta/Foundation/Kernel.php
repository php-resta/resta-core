<?php

namespace Resta\Foundation;

use Resta\ClosureDispatcher;
use Resta\Container\Container;
use Resta\Contracts\KernelContracts;

class Kernel extends Container implements KernelContracts
{
    /**
     * @var $boot
     */
    protected $boot=false;

    /**
     * @var array
     */
    protected $originGroups=[
        'accessor',
        'exception',
        'urlProvider',
        'logger',
        'environment',
        'configProvider',
        'encrypter',
        'eventDispatcher',
        'serviceProvider',
    ];

    protected $consoleGroups = [
        'appConsole',
    ];

    /**
     * @var array
     */
    protected $middlewareGroups=[
        'middleware'
    ];
    /**
     * @var array
     */
    protected $reflectionGroups=[
        'router',
        'responseManager',
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
    ];

    /**
     * @method bootstrappers
     * @param $app \Resta\Contracts\ApplicationContracts
     * @param $strappers bootstrappers
     */
    protected function bootstrappers($app,$strappers)
    {
        //The boot method to be executed can be specified by the user.
        //We use this method to know how to customize it.
        BootFireCallback::setBootFire([$app,$strappers],function($boot){

            //kernel boots run and service container{
            //makeBuild for service Container
            return resta()->appClosureInstance
                ->call(function() use ($boot) {
                    $this->bootFire($boot);
                });
        });
    }

    /**
     * @param $group
     * @param $booting
     */
    public function callBootstrapperProcess($group,$booting,$onion=true)
    {
        if($onion){

            // we will implement a special onion method here and
            // pass our bootstraper classes through this method.
            // Our goal here is to implement the middleware layer correctly.
            $this->makeBind(BootstrapperPeelOnion::class)->onionBoot([$group,$booting],function() use($group){
                $this->bootstrappers($this,$group);
            });

            return false;
        }

        //system booting for app
        //pre-loaders are the most necessary classes for the system.
        $this->bootstrappers($this,$group);
    }
}