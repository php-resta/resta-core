<?php

namespace Migratio\Resource\StubManager;

use Migratio\Schema;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class Stubber
{
    /**
     * @var $config
     */
    protected $config;

    /**
     * @var $schema Schema
     */
    protected $schema;

    /**
     * @var $stubber
     */
    protected $stubber;

    /**
     * @var $file
     */
    protected $file;

    /**
     * Stubber constructor.
     * @param $schema
     */
    public function __construct($schema)
    {
        $this->file     = new Filesystem();
        $this->stubber  = realpath(__DIR__.'/../../Stub');
        $this->schema   = $schema;
        $this->config   = $this->schema->getConfig();
    }

    /**
     * @param $params
     * @return array
     */
    private function getPaths($params)
    {
        if(!isset($params[3])){
            return [$this->config['paths'][0]];
        }

        return [];
    }

    /**
     * @param $executionPath
     * @param $path
     * @param $params
     * @return bool
     */
    private function fopenprocess($executionPath,$path,$params)
    {
        $dt = fopen($executionPath, "r");
        $content = fread($dt, filesize($executionPath));
        fclose($dt);

        foreach ($params as $key=>$value){

            $content=str_replace("__".$key."__",$value,$content);
        }


        $dt = fopen($path, "w");
        fwrite($dt, $content);
        fclose($dt);

        return true;


    }

    /**
     * @param $params
     * @return array
     */
    public function get($params)
    {
        list($table,$name,$type) = $params;

        $results = [];

        $paths = $this->getPaths($params);

        foreach ($paths as $pathKey=>$path){

            $tableDirectory = $path.'/'.ucfirst($table);


            if(!file_exists($tableDirectory)){
                $results['directory'][$pathKey]= $this->fileProcess($tableDirectory,'makeDirectory');
            }
            else{
                $results['directory'][$pathKey]= $this->getResult(false,
                    'Already exist the specified directory');
            }

            $results['directory'][$pathKey]['table']= $table;
            $results['directory'][$pathKey]['directory']= $table;
            $results['directory'][$pathKey]['type']= $type;

            $fileName = ucfirst($name);

            $fileNameWithTime = time().'_'.$fileName;

            $filePath = $tableDirectory.'/'.$fileNameWithTime.'.php';

            if(!file_exists($filePath)){
                $results['file'][$pathKey]=$this->fileProcess($filePath,'makeFile');
            }
            else{
                $results['file'][$pathKey]= $this->getResult(false,
                    'Already exist the specified file');
            }

            $results['file'][$pathKey]['table']=$table;
            $results['file'][$pathKey]['file']=$fileName;
            $results['file'][$pathKey]['type']=$type;

            $stubber = $this->stubber.'/'.$type.'.stub';

            $this->fopenprocess($stubber,$filePath,['className'=>$fileName]);

            $this->file->chmod($tableDirectory,0777,000,true);
        }

        return $results;
    }

    /**
     * @param $path
     * @param $process
     * @return array
     */
    private function fileProcess($path,$process)
    {
        try {
            $this->{$process}($path);
            return $this->getResult(true,null);
        } catch (IOExceptionInterface $exception) {
            return $this->getResult(false,
                "An error occurred while creating your directory at ".$exception->getPath()."");
        }
    }

    /**
     * @param $path
     * @param int $mode
     */
    private function  makeDirectory($path,$mode=0777)
    {
        return $this->file->mkdir($path,$mode);
    }

    /**
     * @param $path
     */
    private function makeFile($path)
    {
        return $this->file->touch($path);
    }

    /**
     * @param $success
     * @param $message
     * @return array
     */
    private function getResult($success,$message)
    {
        return [
            'success'=>$success,
            'message'=>$message
        ];
    }
}

