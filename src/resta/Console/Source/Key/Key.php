<?php

namespace Resta\Console\Source\Key;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;

use Resta\Foundation\StaticPathModel;
use Resta\Support\Utils;
use Symfony\Component\Security\Core\Tests\Encoder\EncAwareUser;

class Key extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='key';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'generate'=>'Creates an application crypt key file'
    ];

    /**
     * @var $commandRule
     */
    public $commandRule=[];

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

        echo $this->classical(' > Your application key file has been successfully created in the '.StaticPathModel::appPath().'');
    }
}