<?php

namespace Resta;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * Class FileSystem
 * @package Resta
 */
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
    public function makeDirectory($data){

        if(file_exists($data->directory['projectDir'])){
            throw new \LogicException('This Project Is Already Available');
        }

        foreach ($data->directory as $directory){

            try {
                $this->fs->mkdir($directory,'07777');
            } catch (IOExceptionInterface $e) {
                return "An error occurred while creating your directory at ".$e->getPath();
            }
        }


    }

}