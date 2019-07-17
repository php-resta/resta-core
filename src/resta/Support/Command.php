<?php

namespace Resta\Support;

use Resta\Foundation\ApplicationProvider;
use Symfony\Component\Process\Process as ProcessHandler;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Command extends ApplicationProvider
{
    /**
     * @var $arguments
     */
    protected $arguments;

    /**
     * Process constructor.
     *
     * @param $command
     * @param $args
     */
    public function __construct($app,$command,$args)
    {
        parent::__construct($app);

        $this->arguments[]  = 'php';
        $this->arguments[]  = 'api';
        $this->arguments    = array_merge($this->arguments,explode(" ",$command));
        $this->arguments[]  = strtolower(app);

        $argsList = explode(' ',$args);

        foreach ($argsList as $item) {
            $this->arguments[]  = $item;
        }
    }

    /**
     * handle application command
     *
     * @return bool
     */
    public function handle()
    {
        $process = new ProcessHandler($this->arguments,root.'');
        $process->start();

        foreach ($process as $type => $data) {
            if ($process::OUT !== $type) {
                return false;
            }
            return true;
        }
    }
}