<?php

namespace Resta;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class FileProcess
{
    /**
     * @var $fs
     */
    public $fs;

    /**
     * @var $stubPath
     */
    public $stubPath;

    /**
     * @var $data
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
        $this->fs=new Filesystem();
        $this->stubPath=__DIR__.'/Console/Stubs';
    }

    /**
     * @param $data
     * @param bool $status
     * @return mixed
     */
    public function makeDirectory($data,$status=false)
    {
        if($data->type=="project" && file_exists($data->project)){
            throw new \LogicException('This Project Is Already Available');
        }
        if(false===$status){

            if($data->type!=="project" && !file_exists($data->project)){
                throw new \LogicException('Project No');
            }
        }
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
            $this->fs->mkdir($directory,'07777');
        } catch (IOExceptionInterface $e) {
            return "An error occurred while creating your directory at ".$e->getPath();
        }
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

        $stubberDirectoryList=app()->path()->stubs();

        foreach ($stubStructure as $stubberDirectory){

            $stubberDirectoryList=$stubberDirectoryList.'/'.$stubberDirectory;

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
}