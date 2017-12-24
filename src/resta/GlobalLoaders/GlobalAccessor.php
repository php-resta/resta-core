<?php

namespace Resta\GlobalLoaders;

use Resta\Response\ResponseApplication;
use Symfony\Component\HttpFoundation\Request;
use Resta\ApplicationProvider;

class GlobalAccessor extends ApplicationProvider  {

    /**
     * @method handle
     */
    public function handle(){

        //get response success and status
        $this->singleton()->instanceController=null;
        $this->singleton()->responseSuccess=true;
        $this->singleton()->responseStatus=200;
        $this->singleton()->responseType='json';

        //we first load the response class as a singleton object to allow you to send output anywhere
        $this->singleton()->out=$this->makeBind(ResponseApplication::class);

        //The HttpFoundation component defines an object-oriented layer for the HTTP specification.
        //The HttpFoundation component replaces these default PHP global variables and functions by an object-oriented layer
        $this->singleton()->request     = Request::createFromGlobals();
        $this->singleton()->get         = $this->app->kernel()->request->query->all();
        $this->singleton()->post        = $this->app->kernel()->request->request->all();

        //We determine with the kernel object which HTTP method the requested from the client
        $this->singleton()->httpMethod=ucfirst(strtolower($this->app->kernel()->request->getRealMethod()));



    }

}