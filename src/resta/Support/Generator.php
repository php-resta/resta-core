<?php

namespace Resta\Support;

use Resta\Exception\FileNotFoundException;

class Generator
{
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
     */
    public function __construct($path=null)
    {
        $this->path = $path;

        $this->namespace = Utils::getNamespace($this->path);

        $this->createPath();

        $this->stubPath = app()->corePath().'Console/Stubs/generator';
    }

    /**
     * creates directory for generator
     *
     * @return mixed|void
     */
    public function createPath()
    {
        if(!file_exists($this->path)){
            if(!files()->makeDirectory($this->path)){
                exception()->runtime($this->path.' makeDirectory fail');
            }
        }
    }

    /**
     * creates file for generator
     *
     * @param null $name
     * @param array $replacement
     *
     * @throws FileNotFoundException
     */
    public function createFile($name=null,$replacement=array())
    {
        if(file_exists($this->path) && !is_null($name)){

            $content = files()->get($this->getStubFile());

            $file = $this->path.''.DIRECTORY_SEPARATOR.''.ucfirst($name).'.php';

            if(files()->put($file,$content)!==FALSE){

                $this->name = $name;
                $this->replaceFileContent($replacement,$file);
            }
        }
    }

    /**
     * get stub file for generator
     *
     * @return string
     */
    public function getStubFile()
    {
        return $this->stubPath.''.DIRECTORY_SEPARATOR.''.$this->type.'.stub';
    }

    /**
     * replace with replacement variables content of the given file
     *
     * @param $replacement
     * @param $file
     * @return void
     *
     * @throws FileNotFoundException
     */
    private function replaceFileContent($replacement,$file)
    {
        $replacementVariables = $this->replacementVariables($replacement);
        $content = files()->get($file);

        foreach ($replacementVariables as $key=>$replacementVariable){
            $search = '/__'.$key.'__/';
            $replace = $replacementVariable;
            $content = preg_replace($search,$replace,$content);
        }
        files()->replace($file,$content);
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

}
