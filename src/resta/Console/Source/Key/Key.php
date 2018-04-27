<?php

namespace Resta\Console\Source\Key;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;

use Resta\StaticPathModel;
use Resta\Utils;
use Symfony\Component\Security\Core\Tests\Encoder\EncAwareUser;

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
        $this->argument['applicationKey']=app()->singleton()->bindings['encrypter']->setCipherText();

        //set key file touch
        $this->file->touch($this);

        return $this->info('Your Application Key File Has Been Successfully Created');
    }
}