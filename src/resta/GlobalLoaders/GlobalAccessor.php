<?php

namespace Resta\GlobalLoaders;

use Resta\Response\ResponseApplication;
use Resta\StaticPathModel;
use Symfony\Component\HttpFoundation\Request;
use Resta\ApplicationProvider;
use Store\Services\RequestService;
use Resta\App;

class GlobalAccessor extends ApplicationProvider  {

    /**
     * @method handle
     */
    public function handle(){

        //class alias for global app class
        class_alias(App::class,'application');
        $this->singleton()->appClass=new \application();

        //get response success and status
        $this->singleton()->instanceController=null;
        $this->singleton()->responseSuccess=true;
        $this->singleton()->responseStatus=200;
        $this->singleton()->responseType='json';

        //we first load the response class as a singleton object to allow you to send output anywhere
        $this->singleton()->out=$this->makeBind(ResponseApplication::class);

        //The HttpFoundation component defines an object-oriented layer for the HTTP specification.
        //The HttpFoundation component replaces these default PHP global variables and functions by an object-oriented layer
        Request::setFactory(function(array $query = array(),
                                     array $request = array(),
                                     array $attributes = array(),
                                     array $cookies = array(),
                                     array $files = array(),
                                     array $server = array(),
                                     $content = null)
        {
            return new RequestService($query,
                $request,
                $attributes,
                $cookies,
                $files,
                $server,
                $content);
        });
        $this->singleton()->request     = Request::createFromGlobals();
        $this->singleton()->get         = $this->app->kernel()->request->query->all();
        $this->singleton()->post        = $this->app->kernel()->request->request->all();

        //We determine with the kernel object which HTTP method the requested from the client
        $this->singleton()->httpMethod=ucfirst(strtolower($this->app->kernel()->request->getRealMethod()));
    }

}