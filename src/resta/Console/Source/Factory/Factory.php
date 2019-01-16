<?php

namespace Resta\Console\Source\Factory;

use Resta\Support\Utils;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Factory extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='factory';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates an application factory'
    ];

    /**
     * @var bool
     */
    protected $projectStatus = true;

    /**
     * @var $commandRule
     */
    protected $commandRule=[[
        'create'=>[]
    ]];

    /**
     * @return void|mixed
     */
    public function create()
    {
        $factoryFile = app()->path()->factory().''.DIRECTORY_SEPARATOR.'Factory.php';

        $this->argument['factoryDir']   = app()->namespace()->factory();
        $factoryArgument                = $this->argument['factory'];

        $this->directory['factoryArgumentDir']              = app()->path()->factory().''.DIRECTORY_SEPARATOR.''.$factoryArgument;
        $this->directory['factoryArgumentDirInterfaces']    = $this->directory['factoryArgumentDir'].''.DIRECTORY_SEPARATOR.'Interfaces';
        $this->directory['factoryArgumentDirResources']     = $this->directory['factoryArgumentDir'].''.DIRECTORY_SEPARATOR.'Resources';

        $this->file->makeDirectory($this);

        if(file_exists( $this->directory['factoryArgumentDir'])){

            $factoryargumentmanager = $this->directory['factoryArgumentDir'].'/'.$factoryArgument.'Manager.php';
            $factoryargumentmanagerInterface = $this->directory['factoryArgumentDirInterfaces'].'/'.$factoryArgument.'Interface.php';

            if(!file_exists($this->directory['factoryArgumentDir'].'/'.$factoryArgument.'Manager.php')){

                $this->touch['factory/factoryargumentmanager'] =   $factoryargumentmanager;
                $this->touch['factory/factoryargumentmanagerinterface']     = $factoryargumentmanagerInterface;
                $this->touch['factory/index'] = $this->directory['factoryArgumentDirResources'].'/index.html';

                $this->file->touch($this);

                Utils::changeClass($factoryFile,
                    ['class Factory extends FactoryManager
{'=>'class Factory extends FactoryManager
{
    /**
     * @return '.$factoryArgument .'Interface
     */
    public static function '.strtolower($factoryArgument ).'() : '.$factoryArgument.'Interface
    {
        return static::singleton('.$factoryArgument.'Manager::class);
    }
    ',
                        'namespace '.$this->argument['factoryDir'].';' => 'namespace '.$this->argument['factoryDir'].';
                                   
use '.$this->argument['factoryDir'].'\\'.$factoryArgument.'\\'.$factoryArgument.'Manager;
use '.$this->argument['factoryDir'].'\\'.$factoryArgument.'\Interfaces\\'.$factoryArgument.'Interface;',

                        '* @package '.$this->argument['factoryDir'].'' => '* @package '.$this->argument['factoryDir'].'
 * @property '.$factoryArgument.'Interface '.strtolower($factoryArgument)

                    ]);
            }

            if(isset($this->argument['resource'])){
                $resourceArgument = $this->argument['resource'];

                if($resourceArgument!==null){
                    $factoryArgumentResourceDir = $this->directory['factoryArgumentDir'].''.DIRECTORY_SEPARATOR.'Resources';
                    $this->directory['factoryArgumentResourceArgumentDir']  =$factoryArgumentResourceDir.''.DIRECTORY_SEPARATOR.''.$resourceArgument;
                    $this->file->makeDirectory($this);

                    if(!file_exists($this->directory['factoryArgumentResourceArgumentDir'].''.DIRECTORY_SEPARATOR.''.$resourceArgument.'.php')){

                        $this->touch['factory/factoryargumentresource']             = $this->directory['factoryArgumentResourceArgumentDir'].''.DIRECTORY_SEPARATOR.''.$resourceArgument.'.php';
                        $this->touch['factory/factoryargumentresourceinterface']    = $this->directory['factoryArgumentDirInterfaces'].'/'.$resourceArgument.'Interface.php';
                        $this->file->touch($this);

                        Utils::changeClass($factoryargumentmanager,
                            ['class '.$factoryArgument.'Manager extends FactoryManager implements '.$factoryArgument.'Interface
{'=>'class '.$factoryArgument.'Manager extends FactoryManager implements '.$factoryArgument.'Interface
{
    /**
     * @return '.$resourceArgument.'Interface
     */
    public function '.strtolower($resourceArgument).'() : '.$resourceArgument.'Interface
    {
        return new '.$resourceArgument.'($this->factory);
    }
    ',

                                'namespace '.$this->argument['factoryDir'].'\\'.$factoryArgument.';' => 'namespace '.$this->argument['factoryDir'].'\\'.$factoryArgument.';
                                   
use '.$this->argument['factoryDir'].'\\'.$factoryArgument.'\Interfaces\\'.$resourceArgument.'Interface;
use '.$this->argument['factoryDir'].'\\'.$factoryArgument.'\Resources\\'.$resourceArgument.'\\'.$resourceArgument.';',

                            ]);


                        Utils::changeClass($factoryargumentmanagerInterface,
                            [
                                'interface '.$factoryArgument.'Interface
{' => 'interface '.$factoryArgument.'Interface
{
    /**
     * @return '.$resourceArgument.'Interface
     */
    public function '.strtolower($resourceArgument).'();
    '
                            ]);
                    }

                }
            }


        }


        echo $this->classical('factory');
    }
}