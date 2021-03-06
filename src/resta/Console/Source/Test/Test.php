<?php

namespace Resta\Console\Source\Test;

use Resta\Support\PhpUnitManager;
use Resta\Support\SimpleXmlManager;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Test extends ConsoleOutputter
{
    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type = 'test';

    /**
     * @var $define
     */
    public $define = 'creates test file for application';

    /**
     * @var $commandRule
     */
    public $commandRule = [];

    /**
     * @method generate
     * @return mixed
     */
    public function create()
    {
        if(isset($this->argument['controller'])){
            $this->argument['test'] = $this->argument['controller'];
        }

        $type = (isset($this->argument['type'])) ? $this->argument['type'] : 'Unit';

        if(!file_exists(app()->path()->tests())){
            $this->directory['test'] = app()->path()->tests();
            $this->file->makeDirectory($this);
        }

        $dirWithType = app()->path()->tests().'/'.$type;


        if(!file_exists($dirWithType)){
            $this->directory['testType'] = $dirWithType;
            $this->file->makeDirectory($this);
        }

        $this->argument['testPath'] = app()->namespace()->tests();
        $this->argument['testDirPath'] = app()->namespace()->tests().'\\'.$type;
        $this->argument['testNamespace'] = ucfirst($this->argument['test']).'Test';
        $this->argument['projectName'] = strtolower($this->projectName());

        if(isset($this->argument['controller']) && is_string($this->argument['controller'])){

            $this->argument['controllerForHttpRequest'] = strtolower($this->argument['controller']);
            $this->touch['test/testforcontroller']= $dirWithType.'/'.$this->argument['testNamespace'].'.php';
        }
        else{
            $this->touch['test/test']= $dirWithType.'/'.$this->argument['testNamespace'].'.php';
        }



        $this->file->touch($this);

        echo $this->classical(' > Test file called as "'.$this->argument['test'].'" has been successfully created in the '.$dirWithType.'');
    }

    /**
     * @return mixed
     */
    public function publish()
    {
        $phpunit = ''.root.'/phpunit.xml';

        $xml = new SimpleXmlManager($phpunit);

        $array = $xml->toArray();

        $new = (new PhpUnitManager($array))->add(strtolower($this->projectName()),'directory',
            str_replace(root.''.DIRECTORY_SEPARATOR,"",app()->path()->tests()));

        $newDataXml = $xml->toXml($new);

        app()->get('fileSystem')->writeFile($phpunit,$newDataXml);

        echo $this->classical(' > phpunit.xml file has been updated');
    }

    /**
     * @return mixed
     */
    public function run()
    {
        $process = new Process(['vendor'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'phpunit','--group',strtolower($this->projectName())]);
        $process->setTty(true);

        try {
            $process->mustRun();

            echo $process->getOutput();
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
        }
    }

    public function phpUnit()
    {

    }
}