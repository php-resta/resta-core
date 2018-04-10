<?php

namespace Resta\Console\Source\Command;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\Utils;

class Command extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='command';

    /**
     * @var $define
     */
    public $define='command';

    /**
     * @var $command_create
     */
    public $command_create='php api command create [project] command:[commandName]';

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        $this->touch['command/file']        = $this->command().'/'.$this->argument['command'].'.php';

        $this->file->touch($this);

        Utils::chmod(root);

        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->bluePrint('Command Class Named ['.$this->argument['command'].'] Has Been Successfully Created');
        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->cyan('   You can see in '.$this->command().' your command class   ');
        echo $this->classical('---------------------------------------------------------------------------');

    }
}