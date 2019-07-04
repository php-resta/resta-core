<?php

namespace Resta\Support;

use Resta\Exception\FileNotFoundException;

class Generator
{
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
     * Generator constructor.
     * @param null $path
     * @param null $fileSystem
     */
    public function __construct($path=null,$fileSystem=null)
    {
        $this->path = $path;

        $this->fileSystem = $fileSystem;

        $this->namespace = Utils::getNamespace($this->path);

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
     * creates file for generator
     *
     * @param null $name
     * @param array $replacement
     *
     * @return Generator
     *
     * @throws FileNotFoundException
     */
    public function createFile($name=null,$replacement=array())
    {
        if(file_exists($this->path) && !is_null($name)){

            $content = $this->fileSystem->get($this->getStubFile());
            $this->file = $this->path.''.DIRECTORY_SEPARATOR.''.ucfirst($name).'.php';

            if($this->fileSystem->put($this->file,$content)!==FALSE){
                $this->name = $name;
                $this->replaceFileContent($replacement,$this->file);
            }
        }

        return $this;
    }

    /**
     * creates method to file for generator
     *
     * @param array $params
     */
    public function createMethod($params=array())
    {

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
     * @return void
     */
    private function replaceFileContent($replacement,$file)
    {
        $replacementVariables = $this->replacementVariables($replacement);
        $content = $this->fileSystem->get($file);

        foreach ($replacementVariables as $key=>$replacementVariable){
            $search = '/__'.$key.'__/';
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
    public function setStubPath($stubPath)
    {
        $this->stubPath = $stubPath;
    }

}
