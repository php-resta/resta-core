<?php

namespace Resta\Foundation;

use Resta\Traits\ApplicationTraits;
use Resta\Contracts\Application as ApplicationContract;

class Application extends Kernel implements ApplicationContract {

    //application traits
    use ApplicationTraits;

    /**
     * @var $environment null
     */
    public $environment;

    /**
     * @var $console null
     */
    public $console;

    /**
     * Application constructor.
     * @param $environment
     * @param bool $console
     */
    public function __construct($environment, $console=false){

        //get environment for app
        //get console status for cli
        $this->environment=$environment;
        $this->console=$console;

        //start boot for app
        //this method is the main fire and is brain for system
        $this->booting();
    }

    /**
     * @method booting
     * @return void
     */
    public function booting(){

        //system booting for app
        $this->bootstrappers($this);
    }


    /**
     * @method handle
     * @return string
     */
    public function handle(){

        return $this->environment;
    }
}