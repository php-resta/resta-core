<?php

namespace Resta\Console\Source\Key;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Boot\Encrypter;
use Resta\StaticPathModel;
use Resta\Utils;

class Key extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='key';

    /**
     * @var $define
     */
    public $define='Key generate on console';

    /**
     * @var $command_create
     */
    public $command_create='php api key generate';

    /**
     * @method generate
     * @return mixed
     */
    public function generate(){

        //key generate file
        $this->touch['main/keygenerate']= StaticPathModel::getEncrypter();

        //key generate code
        $this->argument['applicationKey']=$this->app->app->makeBind(Encrypter::class)->keyGenerate();

        //set key file touch
        $this->file->touch($this);

        return $this->blue('Your Application Key File Has Been Successfully Created');
    }
}