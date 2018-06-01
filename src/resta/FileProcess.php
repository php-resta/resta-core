<?php

namespace Resta;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class FileProcess {

    /**
     * @var $fs
     */
    public $fs;

    /**
     * FileProcess constructor.
     */
    public function __construct()
    {
        $this->fs=new Filesystem();
    }

    /**
     * @method makeDirectory
     * @param $data object
     * @return mixed
     */
    public function makeDirectory($data,$status=false){

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
                $this->fs->mkdir($directory,'07777');
            } catch (IOExceptionInterface $e) {
                return "An error occurred while creating your directory at ".$e->getPath();
            }
        }


    }


    /**
     * @mkdir touch
     * @param $data
     */
    public function touch($data){

        $stubPath=__DIR__.'/Console/Stubs';

        foreach ($data->touch as $execution=>$touch){

            if(file_exists($touch)){

                throw new \LogicException('this key file is already available');
            }

            $this->fs->touch($touch);

            $executionPath=$stubPath.'/'.$execution.'.stub';
            if(file_exists($executionPath)){

                $this->fopenprocess($executionPath,$touch,$data);
            }

        }
    }


    /**
     * @param $executionPath
     * @param $path
     * @param $param
     * @return bool
     */
    public function fopenprocess($executionPath,$path,$param){

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
     * @return mixed
     */
    public function callFile($file=null){

        if(file_exists($file)){

            return require_once($file);
        }

        return null;
    }

}