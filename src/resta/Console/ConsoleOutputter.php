<?php

namespace Resta\Console;

use Resta\Traits\ConsoleColor;
use Resta\Support\FileProcess;
use Resta\Contracts\ConsoleOutputterContracts;
use Resta\Foundation\PathManager\StaticPathList;
use Resta\Foundation\PathManager\StaticPathModel;

class ConsoleOutputter extends ConsolePrepare implements ConsoleOutputterContracts
{
    //console color
    use ConsoleColor;

    /**
     * @var array
     */
    private $foreground_colors = array();

    /**
     * @var array
     */
    private $background_colors = array();

    /**
     * @var string
     */
    public $project;

    /**
     * @var array
     */
    public  $argument = array();

    /**
     * @var object
     */
    public $file;

    /**
     * @var array
     */
    public $directory = array();

    /**
     * @var string
     */
    public $app;

    /**
     * @var object
     */
    public $table;

    /**
     * @var array
     */
    public $touch = array();

    /**
     * ConsoleOutputter constructor.
     * @param $argument
     * @param $app
     */
    public function __construct($argument,$app) {

        // Set up shell colors
        $this->foreground_colors['black'] = '0;30';
        $this->foreground_colors['dark_gray'] = '1;30';
        $this->foreground_colors['blue'] = '0;34';
        $this->foreground_colors['light_blue'] = '1;34';
        $this->foreground_colors['green'] = '0;32';
        $this->foreground_colors['light_green'] = '1;32';
        $this->foreground_colors['cyan'] = '0;36';
        $this->foreground_colors['light_cyan'] = '1;36';
        $this->foreground_colors['red'] = '0;31';
        $this->foreground_colors['light_red'] = '1;31';
        $this->foreground_colors['purple'] = '0;35';
        $this->foreground_colors['light_purple'] = '1;35';
        $this->foreground_colors['brown'] = '0;33';
        $this->foreground_colors['yellow'] = '1;33';
        $this->foreground_colors['light_gray'] = '0;37';
        $this->foreground_colors['white'] = '1;37';

        $this->background_colors['black'] = '40';
        $this->background_colors['red'] = '41';
        $this->background_colors['green'] = '42';
        $this->background_colors['yellow'] = '43';
        $this->background_colors['blue'] = '44';
        $this->background_colors['magenta'] = '45';
        $this->background_colors['cyan'] = '46';
        $this->background_colors['light_gray'] = '47';

        $this->app = $app;
        $this->argument = $argument;
        $this->file = new FileProcess();
        require_once ('ConsoleTable.php');
        $this->table = new \console_table();

        $this->argument['repositoryNamespace']  = app()->namespace()->repository();
        $this->argument['modelNamespace']       = app()->namespace()->model();
        $this->argument['sourceNamespace']      = app()->namespace()->optionalSource();
        $this->argument['versionNamespace']     = app()->namespace()->version();
        $this->argument['serviceAnnotations']   = app()->namespace()->serviceAnnotations();
    }

    // Returns all foreground color names
    public function getForegroundColors() {
        return array_keys($this->foreground_colors);
    }

    // Returns all background color names
    public function getBackgroundColors() {
        return array_keys($this->background_colors);
    }

    public function ReadStdin($prompt=null, $valid_inputs=null, $default = '') {
        while(!isset($input) || (is_array($valid_inputs) && !in_array($input, $valid_inputs)) || ($valid_inputs == 'is_file' && !is_file($input))) {
            echo $this->input($prompt);
            $input = strtolower(trim(fgets(STDIN)));
            if(empty($input) && !empty($default)) {
                $input = $default;
            }
        }
        return $input;
    }

    /**
     * @param array $commander
     * @return mixed|string
     */
    public function exception($commander=array()){
        return $this->error('[['.$commander['argument'].']] parameter is missing for commander');
    }

    /**
     * @param $data
     * @param string $seperate
     * @return mixed|string
     */
    public function checkGroupArgument($data,$seperate="\\"){

        $dataParse=explode("\\",$data);

        if(isset($this->argument['group'])){
            $argument=current($dataParse).''.$seperate.''.$this->argument['group'];
        }
        else{
            $argument=current($dataParse);
        }

        return $argument;
    }

    /**
     * @param $string
     */
    public function consoleLogger($string,$level)
    {
        if(config('app')!==null && config('app.consoleLogger')){

            logger('console')->{$level}([
                'argument'  => implode(" ",core()->consoleArguments),
                'result'    => $string,
            ]);
        }

    }
}