<?php

namespace Resta\Support\Generator;

use Resta\Support\Utils;
use Resta\Support\Filesystem;

abstract class GeneratorAbstract
{
    /**
     * @var null|array
     */
    protected $classProperties;

    /**
     * @var null|string
     */
    protected $data;

    /**
     * @var null|string
     */
    protected $file;

    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * @var null|string
     */
    protected $path;

    /**
     * @var null|string
     */
    protected $name;

    /**
     * @var null|string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $type = 'class';

    /**
     * @var string
     */
    protected $format;

    /**
     * @var null|string
     */
    protected $stubPath;

    /**
     * @var array
     */
    protected $accessibleProperties = array();

    /**
     * @var array
     */
    protected $methodParameters = array();

    /**
     * @var array
     */
    protected $loaded = [];

    /**
     * @var null|object
     */
    protected static $instance;

    /**
     * GeneratorAbstract constructor.
     * @param $path
     * @param $name
     * @param Filesystem $fileSystem
     */
    public function __construct($path,$namespace,$name,Filesystem $fileSystem=null)
    {
        $this->format = $this->type;

        $this->path = $path;

        $this->name = $name;

        $this->file = $this->path.''.DIRECTORY_SEPARATOR.''.ucfirst($this->name).'.php';

        $this->fileSystem = (is_null($fileSystem)) ? files() : $fileSystem;

        $this->namespace = $namespace;

        $this->setStubPath();

        $this->createPath();
    }

    /**
     * creates directory for generator
     *
     * @return mixed|void
     */
    public function createPath()
    {
        if(!file_exists($this->path)){
            if(!$this->fileSystem->makeDirectory($this->path)){
                throw new \Error($this->path.' makeDirectory fail');
            }
        }
    }

    /**
     * get class instance for generator
     *
     * @return mixed|void
     */
    protected function getClassInstance()
    {
        if(!isset($this->loaded['createClass'])){

            if(is_null(self::$instance)){
                $class = Utils::getNamespace($this->file);
                self::$instance = new $class;
            }
        }

        return self::$instance;
    }

    /**
     * get accessible method value for generator
     *
     * @param $method
     * @return mixed|string
     */
    protected function getAccessibleMethodValue($method)
    {
        return  (isset($this->accessibleProperties[$method])) ?
            $this->accessibleProperties[$method]
            : 'public';
    }

    /**
     * get class string from generator
     *
     * @return null|string
     *
     * @throws \Resta\Exception\FileNotFoundException
     */
    protected function getClassString()
    {
        if(preg_match('@'.$this->type.'\s.*\n{@',$this->fileSystem->get($this->file),$parse)){
            return $parse[0];
        }

        return null;
    }

    /**
     * get eval value
     *
     * @param null $eval
     * @return string
     */
    protected function getEval($eval=null)
    {
        return __DIR__ . '/stubs/evals/' .$eval.'.stub';
    }

    /**
     * get parameters method value for generator
     *
     * @param $method
     * @param bool $regexEscape
     * @return mixed|string
     */
    protected function getMethodParameters($method,$regexEscape=true)
    {
        return  (isset($this->methodParameters[$method])) ?
            ($regexEscape) ? $this->regexEscape($this->methodParameters[$method]) : $this->methodParameters[$method]
            : '';
    }

    /**
     * get stub file for generator
     *
     * @return string
     */
    public function getStubFile()
    {
        $stubFile = $this->stubPath.''.DIRECTORY_SEPARATOR.''.$this->format.'.stub';

        if(!file_exists($stubFile)){
            throw new \Error($stubFile.' path is not available');
        }

        return $stubFile;
    }

    /**
     * escapes to regex data for generator
     *
     * @param $data
     * @return mixed
     */
    protected function regexEscape($data)
    {
        $dataEscape = str_replace('\\','\\\\',$data);
        $dataEscape = str_replace('$','\$',$dataEscape);
        $dataEscape = str_replace('()','\(\)',$dataEscape);
        $dataEscape = str_replace('[]','\[\]',$dataEscape);


        return $dataEscape;
    }

    /**
     * replace with replacement variables content of the given file
     *
     * @param $replacement
     * @param $file
     * @param bool $default
     * @return void
     *
     * @throws \Resta\Exception\FileNotFoundException
     */
    protected function replaceFileContent($replacement,$file,$default=false)
    {
        $replacementVariables = ($default) ? $replacement : $this->replacementVariables($replacement);
        $content = $this->fileSystem->get($file);

        foreach ($replacementVariables as $key=>$replacementVariable){

            if($default){
                $search = '/'.$key.'/';
            }
            else{
                $search = '/__'.$key.'__/';
            }

            $replace = $replacementVariable;
            $content = preg_replace($search,$replace,$content);
        }
        $this->fileSystem->replace($file,$content);
    }

    /**
     * get replacement variables
     *
     * @param array $replacement
     * @return array
     */
    protected function replacementVariables($replacement=array())
    {
        $replacement['namespace'] = $this->namespace;
        $replacement['class'] = $this->name;

        return array_map(function($item){
            return ucfirst($item);
        },$replacement);
    }

    /**
     * set format for generator
     *
     * @param $format
     */
    public function setFormat($format)
    {
        $this->format = $format;

        if($this->format=='abstract'){
            $this->type = 'abstract class';
        }

        if($this->format=='interface'){
            $this->type = $this->format;
        }
    }

    /**
     * set stub path
     *
     * @param $stubPath
     */
    public function setStubPath($stubPath=null)
    {
        if(is_null($stubPath)){
            $this->stubPath = __DIR__.'/stubs';
        }
        else{
            $this->stubPath = $stubPath;
        }
    }
}