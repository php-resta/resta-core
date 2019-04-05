<?php

namespace Resta\Encrypter;

use Defuse\Crypto\Key;
use Resta\Support\Utils;
use Defuse\Crypto\Crypto;
use Resta\Foundation\ApplicationProvider;
use Resta\GlobalLoaders\Encrypter as EncrypterGlobalInstance;

class EncrypterProvider extends ApplicationProvider
{
    /**
     * encrypter provider handle
     *
     * @return mixed
     *
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException
     */
    public function handle()
    {
        //set define for encrypter
        define('encrypter',true);

        //get encrypter file
        $encrypterFile = path()->encrypterFile();

        //throws an exception it there is no encrypter file
        if(!file_exists($encrypterFile)){
            exception()->domain('The Application key is invalid');
        }

        //We invite our existing encrypter file
        $appKeyFile = Utils::yaml($encrypterFile)->get();

        //we are checking two values ​​for key comparison.1.st separated value encryption
        //key second separated value crypto closure value
        $appKey = explode("__",$appKeyFile['key']);

        //we are assigning a singleton object
        //so that we can use our application key in the project.
        $this->setApplicationKey($appKey);

        //If the crypto decrypts when we get a false error, we stop the automatic application
        return Crypto::Decrypt($appKey[0], unserialize(base64_decode($appKey[1])));
    }

    /**
     * register to container for encrypter
     *
     * @param $key
     * @return void
     */
    private function setApplicationKey($key)
    {
        //we are assigning a singleton object
        //so that we can use our application key in the project.
        $this->app->register('applicationKey',current($key));
    }


    /**
     * set chipher text for application
     *
     * @return string
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function setCipherText()
    {
        //If you create a key via console, you will get here the booted class.
        //Then,The command follows the process to create a yaml crypto file.
        $key = Key::createNewRandomKey();
        return Crypto::Encrypt('resta', $key).'__'.base64_encode(serialize($key));
    }

}