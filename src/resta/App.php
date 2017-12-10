<?php

namespace Resta;

class App {

    public $app;

    public function __construct($app=null){

        $this->app=$app;
    }

    public function get(){
        return 'hello world';
    }
}