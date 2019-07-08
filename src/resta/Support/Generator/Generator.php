<?php

namespace Resta\Support\Generator;

use Resta\Exception\FileNotFoundException;

class Generator extends GeneratorAbstract
{
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
            $this->loaded['createClass'] = true;

            if($this->fileSystem->put($this->file,$content)!==FALSE){
                $this->replaceFileContent($replacement,$this->file);
            }
        }

        return $this;
    }

    /**
     * creates class document for generator
     *
     * @param array $documents
     *
     * @throws FileNotFoundException
     */
    public function createClassDocument($documents=array())
    {
        if(isset($this->loaded['createClass']) && count($documents)){
            $content = '<?php'.$this->fileSystem->get($this->getEval('createClassDocument'));
            eval("?>$content");
        }
    }

    /**
     * create class extend object for generator
     *
     * @param $namespace
     * @param $alias
     *
     * @throws FileNotFoundException
     */
    public function createClassExtend($namespace, $alias)
    {
        if(!is_null($namespace) && !is_null($alias) && !preg_match('@extends@',$this->getClassString())){
            if(preg_match('@class\s(.*?).*@',$this->getClassString(),$class)){
                $statements = explode(' ',$class[0]);
                $className = $statements[1];

                if(count($statements)>2){
                    $implements = implode(' ',array_slice($statements,2));
                }
            }

            if(isset($className)){
                $this->createClassUse([
                    $namespace
                ]);

                if(!isset($implements)){
                    $content = '<?php'.$this->fileSystem->get($this->getEval('createClassExtend'));
                    eval("?>$content");
                }
                else{
                    $content = '<?php'.$this->fileSystem->get($this->getEval('createClassExtendWithImplements'));
                    eval("?>$content");
                }
            }
        }
    }

    /**
     * create class interface object for generator
     *
     * @param array $implements
     *
     * @throws FileNotFoundException
     */
    public function createClassImplements($implements=array())
    {
        if(!is_null($this->getClassString())){

            $implementList = [];
            $implementUseList = [];

            foreach($implements as $namespace=>$alias) {
                $implementUseList[] = $namespace;
                $implementList[] = $alias;
            }

            $this->createClassUse($implementUseList);

            $content = '<?php'.$this->fileSystem->get($this->getEval('createClassImplements'));
            eval("?>$content");
        }
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

        if(isset($this->loaded['createMethod'])){
            $this->classProperties = $properties;
            $loadedMethod = true;
        }

        if($loadedMethod){
            foreach ($this->classProperties as $property) {
                if(!preg_match('@'.$this->regexEscape($property).'@',$this->fileSystem->get($this->file))){
                    $content = '<?php'.$this->fileSystem->get($this->getEval('createClassProperty'));
                    eval("?>$content");
                }
            }

            if(isset($this->loaded['createClassPropertyDocument'])){
                $this->createClassPropertyDocument($this->loaded['createClassPropertyDocument']);
            }
        }
    }

    /**
     * creates class property document for generator
     *
     * @param array $properties
     *
     * @throws FileNotFoundException
     */
    public function createClassPropertyDocument($properties=array())
    {
        $this->loaded['createClassPropertyDocument'] = $properties;

        foreach ($properties as $property=>$documents){
            $content = '<?php'.$this->fileSystem->get($this->getEval('createClassPropertyDocument'));
            eval("?>$content");
        }
    }

    /**
     * creates class trait for generator
     *
     * @param $trait
     *
     * @throws FileNotFoundException
     */
    public function createClassTrait($trait)
    {
        if(isset($this->loaded['createClass']) && is_string($trait)){
            $content = '<?php'.$this->fileSystem->get($this->getEval('createClassTrait'));
            eval("?>$content");
        }
    }

    /**
     * creates class use statements for generator
     *
     * @param array $uses
     *
     * @throws FileNotFoundException
     */
    public function createClassUse($uses=array())
    {
        if(!is_null($this->getClassString()) && count($uses)){
            $content = '<?php'.$this->fileSystem->get($this->getEval('createClassUse'));
            eval("?>$content");
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
        if(preg_match('@class\s.*\n{@',$this->fileSystem->get($this->file),$parse) && count($methods)){
            $content = '<?php'.$this->fileSystem->get($this->getEval('createMethod'));
            eval("?>$content");

            $this->createClassProperty([],true);
            $this->loaded['createMethod'] = true;
        }
    }

    /**
     * accessible properties method for generator
     *
     * @param array $methods
     * @return void|mixed
     *
     * @throws FileNotFoundException
     */
    public function createMethodAccessibleProperty($methods=array())
    {
        foreach($methods as $method=>$accessibleValue){
            $this->accessibleProperties[$method] = $accessibleValue;
            $content = '<?php'.$this->fileSystem->get($this->getEval('createMethodAccessibleProperty'));
            eval("?>$content");
        }
    }

    /**
     * creates method for generator
     *
     * @param array $methods
     *
     * @throws FileNotFoundException
     */
    public function createMethodBody($methods=array())
    {
        foreach ($methods as $method=>$body){
            $content = '<?php'.$this->fileSystem->get($this->getEval('createMethodBody'));
            eval("?>$content");
        }
    }

    /**
     * creates method for generator
     *
     * @param array $methods
     *
     * @throws FileNotFoundException
     */
    public function createMethodDocument($methods=array())
    {
        foreach ($methods as $method=>$documents){
            $content = '<?php'.$this->fileSystem->get($this->getEval('createMethodDocument'));
            eval("?>$content");
        }
    }

    /**
     * accessible properties method for generator
     *
     * @param array $methods
     * @return void|mixed
     *
     * @throws FileNotFoundException
     */
    public function createMethodParameters($methods=array())
    {
        foreach($methods as $method=>$parameter) {
            $this->methodParameters[$method] = $parameter;
            $content = '<?php'.$this->fileSystem->get($this->getEval('createMethodParameters'));
            eval("?>$content");
        }
    }

}
