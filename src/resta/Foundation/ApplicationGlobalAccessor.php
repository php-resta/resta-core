<?php

namespace Resta\Foundation;

use Resta\Support\Utils;
use Resta\Support\FileProcess;
use Store\Services\RequestService;
use Resta\Response\ResponseApplication;
use Symfony\Component\HttpFoundation\Request;

class ApplicationGlobalAccessor extends ApplicationProvider
{
    /**
     * @return void
     */
    public function handle()
    {
        //get response success and status
        $this->register('instanceController',       null);
        $this->register('responseSuccess',          true);
        $this->register('responseStatus',           200);
        $this->register('responseType',             'json');

        //we first load the response class as a singleton object to allow you to send output anywhere
        $this->register('out',             $this->makeBind(ResponseApplication::class));

        //The HttpFoundation component defines an object-oriented layer for the HTTP specification.
        //The HttpFoundation component replaces these default PHP global variables and functions by an object-oriented layer
        if(Utils::isNamespaceExists(RequestService::class)){

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
        }


        //After registering the symfony request method, we also save the get and post methods for user convenience.
        $this->register('request',      Request::createFromGlobals());
        $this->register('get',          $this->app->kernel()->request->query->all());
        $this->register('post',         $this->app->kernel()->request->request->all());

        //We determine with the kernel object which HTTP method the requested from the client
        $this->register('httpMethod',ucfirst(strtolower($this->app->kernel()->request->getRealMethod())));

        define('httpMethod',strtoupper($this->singleton()->httpMethod));

        $this->register('fileSystem',new FileProcess());
    }
}