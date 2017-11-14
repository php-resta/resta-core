<?php

namespace Resta\Contracts;

interface ApplicationContracts {

    /**
     * @method booting
     * @return mixed
     */
    public function booting();

    /**
     * @method handle
     * @return mixed
     */
    public function handle();

    /**
     * @method bind
     * @param $object null
     * @param $callback null
     * @return mixed
     */
    public function bind($object=null,$callback=null);

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


}