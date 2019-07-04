<?php

namespace Resta\Support;

use Nette\PhpGenerator\PhpNamespace;
use Resta\Exception\FileNotFoundException;

class Generator
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
     * @var null|object
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
     * Generator constructor.
     * @param null|string $path
     * @param null|string $name
     * @param null|object $fileSystem
     */
    public function __construct($path=null,$name=null,$fileSystem=null)
    {
        $this->path = $path;

        $this->name = $name;

        $this->file = $this->path.''.DIRECTORY_SEPARATOR.''.ucfirst($this->name).'.php';

        $this->fileSystem = (is_null($fileSystem)) ? files() : $fileSystem;

        $this->namespace = Utils::getNamespace($this->path);

        $this->setStubPath();

        $this->createPath();
    }

    /**
     * creates class for generator
     *
     * @param array $replacement
     * @return Generator
     *
     * @throws FileNotFoundException
     */
    public function createClass($replacement=array())
    {
        if(file_exists($this->path)){

            $content = $this->fileSystem->get($this->getStubFile());

            if($this->fileSystem->put($this->file,$content)!==FALSE){
                $this->replaceFileContent($replacement,$this->file);
            }
        }

        return $this;
    }

    /**
     * creates class property for generator
     *
     * @param array $properties
     * @param bool $loadedMethod
     *
     * @throws FileNotFoundException
     */
    public function createClassProperty($properties=array(),$loadedMethod=false)
    {
        if(is_null($this->classProperties)){
            $this->classProperties = $properties;
        }

        if($loadedMethod){

            foreach ($this->classProperties as $property) {

                if(preg_match('@class\s.*\n{@',$this->fileSystem->get($this->file),$parse)){
                    $this->replaceFileContent([
                        $parse[0] => $parse[0].' 
    '.$property.'
    '

                    ],$this->file,true);
                }


            }
        }
    }

    /**
     * creates method for generator
     *
     * @param array $methods
     *
     * @throws FileNotFoundException
     */
    public function createMethod($methods=array())
    {
        $list = [];

        foreach ($methods as $method){

            $list[] = '
    '.$this->getAccessibleMethodValue($method).' function '.$method.'('.$this->getMethodParameters($method).')
    {
        return true;
    }
            ';
        }
        if(preg_match('@class\s.*\n{@',$this->fileSystem->get($this->file),$parse)){
            $this->replaceFileContent([
                $parse[0] => $parse[0].' '.implode('',$list)
            ],$this->file,true);

            $this->createClassProperty([],true);
        }

    }

    /**
     * accessible properties method for generator
     *
     * @param array $methods
     * @return void|mixed
     */
    public function createMethodAccessibleProperty($methods=array())
    {
        foreach($methods as $method=>$accessibleValue)
        {
            $this->accessibleProperties[$method] = $accessibleValue;

            $this->replaceFileContent([

                'public function '.$method.'' => ''.$this->getAccessibleMethodValue($method).' function '.$method.''

            ],$this->file,true);
        }
    }

    /**
     * creates method for generator
     *
     * @param array $methods
     */
    public function createMethodBody($methods=array())
    {
        foreach ($methods as $method=>$body){

            $this->replaceFileContent([
                ''.$this->getAccessibleMethodValue($method).' function '.$method.'\('.$this->getMethodParameters($method).'\)\n.*{\n.*\n.*}' => ''.$this->getAccessibleMethodValue($method).' function '.$method.'('.$this->getMethodParameters($method).')
    {
        '.$body.'
    }'
            ],$this->file,true);
        }
    }

    /**
     * creates method for generator
     *
     * @param array $methods
     */
    public function createMethodDocument($methods=array())
    {
        foreach ($methods as $method=>$documents){

            $documentString = [];
            $documentString[] = '/**';

            foreach ($documents as $document){
                $documentString[] = '
     * '.$document.'';
            }

            $documentString[] = '
     */';

            $this->replaceFileContent([
                ''.$this->getAccessibleMethodValue($method).' function '.$method.'\('.$this->getMethodParameters($method).'\)' => ''.implode('',$documentString).'
    '.$this->getAccessibleMethodValue($method).' function '.$method.'('.$this->getMethodParameters($method).')'
            ],$this->file,true);
        }
    }

    /**
     * accessible properties method for generator
     *
     * @param array $methods
     * @return void|mixed
     */
    public function createMethodParameters($methods=array())
    {
        foreach($methods as $method=>$parameter)
        {
            $this->methodParameters[$method] = $parameter;

            $this->replaceFileContent([

                ''.$this->getAccessibleMethodValue($method).' function '.$method.'\(\)' => ''.$this->getAccessibleMethodValue($method).' function '.$method.'('.$parameter.')'

            ],$this->file,true);
        }
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
     * get accessible method value for generator
     *
     * @param $method
     * @return mixed|string
     */
    private function getAccessibleMethodValue($method)
    {
        return  (isset($this->accessibleProperties[$method])) ?
            $this->accessibleProperties[$method]
            : 'public';
    }

    /**
     * get parameters method value for generator
     *
     * @param $method
     * @return mixed|string
     */
    private function getMethodParameters($method)
    {
        return  (isset($this->methodParameters[$method])) ?
            str_replace('$','\$',$this->methodParameters[$method])
            : '';
    }

    /**
     * get stub file for generator
     *
     * @return string
     */
    public function getStubFile()
    {
        $stubFile = $this->stubPath.''.DIRECTORY_SEPARATOR.''.$this->type.'.stub';

        if(!file_exists($stubFile)){
            throw new \Error($stubFile.' path is not available');
        }

        return $stubFile;
    }

    /**
     * replace with replacement variables content of the given file
     *
     * @param $replacement
     * @param $file
     * @param bool $default
     * @return void
     */
    private function replaceFileContent($replacement,$file,$default=false)
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
    private function replacementVariables($replacement=array())
    {
        $replacement['namespace'] = $this->namespace;
        $replacement['class'] = $this->name;

        return array_map(function($item){
            return ucfirst($item);
        },$replacement);
    }

    /**
     * set stub path
     *
     * @param $stubPath
     */
    public function setStubPath($stubPath=null)
    {
        if(is_null($stubPath)){
            $this->stubPath = app()->corePath().'Console/Stubs/generator';
        }
        else{
            $this->stubPath = $stubPath;
        }
    }

}
