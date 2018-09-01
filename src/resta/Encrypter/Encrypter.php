<?php

namespace Resta\Encrypter;

use Resta\Utils;
use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;
use Resta\GlobalLoaders\Encrypter as EncrypterGlobalInstance;

class Encrypter {

    /**
     * @return mixed
     * @param EncrypterGlobalInstance $encrypter
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException
     */
    public function handle(EncrypterGlobalInstance $encrypter){

        //set define for encrypter
        define('encrypter',true);

        //get encrypter file
        $encrypterFile=app()->path()->encrypterFile();

        //throws an exception it there is no encrypter file
        if(!file_exists($encrypterFile)){
            exception()->domain('The Application key is invalid');
        }

        //We invite our existing encrypter file
        $appKeyFile=Utils::getYaml($encrypterFile);

        //we are checking two values ​​for key comparison.1.st separated value encryption
        //key second separated value crypto closure value
        $appKey=explode("__",$appKeyFile['key']);

        //we are assigning a singleton object
        //so that we can use our application key in the project.
        $encrypter->setApplicationKey($appKey);

        //If the crypto decrypts when we get a false error, we stop the automatic application
        return Crypto::Decrypt($appKey[0], unserialize(base64_decode($appKey[1])));
    }


    /**
     * @return string
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function setCipherText(){

        //If you create a key via console, you will get here the booted class.
        //Then,The command follows the process to create a yaml crypto file.
        $key = Key::createNewRandomKey();
        return Crypto::Encrypt('resta', $key).'__'.base64_encode(serialize($key));
    }

}