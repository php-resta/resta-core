<?php

namespace Resta\Contracts;

interface ApplicationContracts {

    /**
     * @method bind
     * @param $object null
     * @param $callback null
     * @return mixed
     */
    public function bind($object=null,$callback=null);


    /**
     * @method container
     * @param $object null
     * @param $callback null
     * @return mixed
     */
    public function container($object=null,$callback=null);

    /**
     * @method singleton
     * @return mixed
     */
    public function singleton();

    /**
     * @method kernel
     * @return mixed
     */
    public function kernel();

    /**
     * @method console
     * @return mixed
     */
    public function console();


    /**
     * @param $make
     * @return mixed
     */
    public function applicationProviderBinding($make);


}