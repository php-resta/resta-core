<?php

namespace Resta\Support;

use Symfony\Component\Process\Process as ProcessHandler;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Process
{
    /**
     * command process
     *
     * @param null|string $command
     * @return mixed|void
     */
    public function command($command=null)
    {
        $process = new ProcessHandler($command,root.'');
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        echo $process->getOutput();
    }
}