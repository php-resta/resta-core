<?php

namespace Resta\Support;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class FileProcess
{
    /**
     * @var object
     */
    public $fs;

    /**
     * @var string
     */
    public $stubPath;

    /**
     * @var string
     */
    protected $data;

    /**
     * @var array
     */
    protected $stubList=array();

    /**
     * FileProcess constructor.
     */
    public function __construct()
    {
        $this->fs = new Filesystem();
        $this->stubPath = app()->corePath().'/Console/Stubs';
    }

    /**
     * @param $file
     * @param $data
     * @return bool
     */
    public function dumpFile($file,$data)
    {
        try {
            $this->fs->dumpFile($file,$data);
            return true;
        } catch (IOExceptionInterface $exception) {
            return false;
        }
    }

    /**
     * @param $data
     * @param bool $status
     * @return mixed
     */
    public function makeDirectory($data,$status=false)
    {
        foreach ($data->directory as $directory){
            try {
                $this->fs->mkdir($directory,'0777');
                chmod($directory,0777);
            } catch (IOExceptionInterface $e) {
                return "An error occurred while creating your directory at ".$e->getPath();
            }
        }
    }

    /**
     * @param $directory
     * @return mixed
     */
    public function setDirectory($directory)
    {
        try {
            $this->fs->mkdir($directory);
        } catch (IOExceptionInterface $e) {
            return "An error occurred while creating your directory at ".$e->getPath();
        }
    }

    /**
     * @param $file
     */
    public function setFile($file)
    {
        @touch($file);
    }

    /**
     * @param $data
     * @param array $complex
     */
    public function touch($data,$complex=array())
    {
        $this->data=$data;

        if(isset($complex['stub']) && isset($this->data->argument['stub'])){

            $this->stubManager($complex);
        }

        $execArray=(count($this->stubList)) ? $this->stubList : $this->data->touch;

        foreach ($execArray as $execution=>$touch){

            if(!file_exists($touch) && $touch!==null){
                touch($touch);

                $executionPath=$this->stubPath.'/'.$execution.'.stub';
                if(file_exists($executionPath)){
                    $this->fopenprocess($executionPath,$touch,$data);
                }
            }
        }
    }

    /**
     * @param array $complex
     */
    private function stubManager($complex=array())
    {
        $stubStructure      = explode("_",$complex['stub']);
        $stubStructure[]    = $this->data->argument['stub'];

        $stubberDirectoryList=path()->stubs();

        foreach ($stubStructure as $stubberDirectory){

            $stubberDirectoryList = $stubberDirectoryList.'/'.$stubberDirectory;

            $this->setDirectory($stubberDirectoryList);
        }

        foreach ($this->data->touch as $execution=>$executionFile){

            $executionArray=explode("/",$execution);

            $executionStub                      = end($executionArray).'';
            $this->stubList[$executionStub]     = $executionFile;
            $stubberFile                        = $stubberDirectoryList.'/'.$executionStub.'.stub';

            $originalPath=$this->stubPath.'/'.$execution.'.stub';

            if(!file_exists($stubberFile)){

                $this->fs->copy($originalPath,$stubberFile);
            }
        }

        $this->stubPath=$stubberDirectoryList;
    }


    /**
     * @param $executionPath
     * @param $path
     * @param $param
     * @return bool
     */
    public function fopenprocess($executionPath,$path,$param)
    {
        $dt = fopen($executionPath, "r");
        $content = fread($dt, filesize($executionPath));
        fclose($dt);

        foreach ($param->argument as $key=>$value){

            $content=str_replace("__".$key."__",$value,$content);
            $content=str_replace("__".$key."-low__",strtolower($value),$content);
        }

        $dt = fopen($path, "w");
        fwrite($dt, $content);
        fclose($dt);

        return true;
    }

    /**
     * @param $executionPath
     * @param $path
     * @param $param
     * @return bool
     */
    public function stubCopy($executionPath,$path,$param)
    {
        $dt = fopen($executionPath, "r");
        $content = fread($dt, filesize($executionPath));
        fclose($dt);

        foreach ($param->argument as $key=>$value){
            $content=str_replace("__".$key."__",$value,$content);
        }

        $dt = fopen($path, "w");
        fwrite($dt, $content);
        fclose($dt);

        return true;
    }

    /**
     * @param null $file
     * @return mixed|null
     */
    public function callFile($file=null)
    {
        if(file_exists($file)){
            return require_once($file);
        }
        return null;
    }

    /**
     * write to file for data
     *
     * @param null|string $file
     * @param null|string $data
     */
    public function writeFile($file=null,$data=null)
    {
        if(!is_null($file) && !is_null($data)){

            $dt = fopen($file, "r");
            $content = fread($dt, filesize($file));
            fclose($dt);

            $dt = fopen($file, "w");
            fwrite($dt, $data);
            fclose($dt);
        }
    }
}