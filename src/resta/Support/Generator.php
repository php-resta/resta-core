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
     * @var array
     */
    protected $loaded = [];

    /**
     * @var null|object
     */
    protected static $instance;

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
        if(isset($this->loaded['createClass'])){

            $documentString = [];
            $documentString[] = '/**';

            foreach ($documents as $document) {

                $documentString[] = '
* ' . $document . '';

            }

            $documentString[] = '
*/';

            $this->replaceFileContent([
                'class\s.*\n{' => implode('',$documentString).'
'.$this->getClassString()
            ],$this->file,true);

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
        if(!preg_match('@extends@',$this->getClassString())){

            if(preg_match('@class\s(.*?).*@',$this->getClassString(),$class)){

                $statements = explode(' ',$class[0]);

                $className = $statements[1];

                if(count($statements)>2){
                    $implements = implode(' ',array_slice($statements,2));
                }
            }
            $this->createClassUse([
                $namespace
            ]);

            if(isset($implements)){

                $this->replaceFileContent([
                    'class\s.*\n{' =>'class '.$className.' extends '.$alias.' '.$implements.'
{'
                ],$this->file,true);
            }
            else{

                $this->replaceFileContent([
                    'class\s.*\n{' =>'class '.$className.' extends '.$alias.'
{'
                ],$this->file,true);
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

            foreach($implements as $namespace=>$alias)
            {
                $implementUseList[] = $namespace;

                $implementList[] = $alias;
            }

            $this->createClassUse($implementUseList);

            if(preg_match('@class.*(.*?).*@',$this->getClassString(),$strings)){
                $statements = explode(' ',$strings[0]);

                $className = $statements[1];

                if(in_array('extends',$statements) && !in_array('implements',$statements)){
                    $extendsAliasName = $statements[array_search('extends',$statements)+1];

                    $this->replaceFileContent([
                        'class\s.*\n{' =>'class '.$className.' extends '.$extendsAliasName.' implements '.implode(',',$implementList).'
{'
                    ],$this->file,true);
                }
                elseif(in_array('extends',$statements) && in_array('implements',$statements)){

                    $extendsAliasName = $statements[array_search('extends',$statements)+1];

                    $this->replaceFileContent([
                        'class\s.*\n{' =>'class '.$className.' extends '.$extendsAliasName.' implements '.end($statements).','.implode(',',$implementList).'
{'
                    ],$this->file,true);
                }
                elseif(!in_array('extends',$statements) && in_array('implements',$statements)){

                    $this->replaceFileContent([
                        'class\s.*\n{' =>'class '.$className.' implements '.end($statements).','.implode(',',$implementList).'
{'
                    ],$this->file,true);
                }
                else{

                    $this->replaceFileContent([
                        'class\s.*\n{' =>'class '.$className.' implements '.implode(',',$implementList).'
{'
                    ],$this->file,true);

                }

            }
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

                    if(preg_match('@class\s.*\n{@',$this->fileSystem->get($this->file),$parse)){
                        $this->replaceFileContent([
                            $parse[0] => $parse[0].' 
    '.$property.'
    '

                        ],$this->file,true);
                    }
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

            $documentString = [];
            $documentString[] = '/**';

            foreach ($documents as $document){
                $documentString[] = '
     * '.$document.'';
            }

            $documentString[] = '
     */';

            $this->replaceFileContent([
                $this->regexEscape($property) => implode('',$documentString).'
    '.$property
            ],$this->file,true);
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
        if(isset($this->loaded['createClass'])){

            $this->replaceFileContent([
                'class\s.*\n{' => $this->getClassString().' 
    use '.$trait.'
    '
            ],$this->file,true);
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
        if(!is_null($this->getClassString())){

            $useString = [];

            foreach ($uses as $use) {

                $useString[] = '
use ' . $use . ';';
            }

            $this->replaceFileContent([
                'namespace '.$this->regexEscape($this->namespace).';' => 'namespace '.$this->namespace.';               
'.implode('',$useString).''
            ],$this->file,true);

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

            if(!preg_match('@function.*'.$method.'@',$this->getClassString())){

                $list[] = '
    '.$this->getAccessibleMethodValue($method).' function '.$method.'('.$this->getMethodParameters($method).')
    {
        return true;
    }
            ';
            }


        }
        if(preg_match('@class\s.*\n{@',$this->fileSystem->get($this->file),$parse)){
            $this->replaceFileContent([
                $parse[0] => $parse[0].' '.implode('',$list)
            ],$this->file,true);

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
     *
     * @throws FileNotFoundException
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
     *
     * @throws FileNotFoundException
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
    '.$this->getAccessibleMethodValue($method).' function '.$method.'('.$this->getMethodParameters($method,false).')'
            ],$this->file,true);
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
     * get class instance for generator
     *
     * @return mixed|void
     */
    public function getClassInstance()
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
     * get class string from generator
     *
     * @return null|string
     *
     * @throws FileNotFoundException
     */
    public function getClassString()
    {
        if(preg_match('@class\s.*\n{@',$this->fileSystem->get($this->file),$parse)){
            return $parse[0];
        }

        return null;
    }

    /**
     * get parameters method value for generator
     *
     * @param $method
     * @param bool $regexEscape
     * @return mixed|string
     */
    private function getMethodParameters($method,$regexEscape=true)
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
        $stubFile = $this->stubPath.''.DIRECTORY_SEPARATOR.''.$this->type.'.stub';

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
    public function regexEscape($data)
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
     * @throws FileNotFoundException
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
