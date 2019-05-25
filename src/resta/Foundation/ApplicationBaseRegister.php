<?php

namespace Resta\Foundation;

use Resta\Support\App;
use Resta\Support\Macro;
use Resta\Support\Utils;
use League\Pipeline\Pipeline;
use Resta\Support\FileProcess;
use Resta\Exception\ErrorProvider;
use Resta\Support\ClassAliasGroup;
use Resta\Support\ReflectionProcess;
use Resta\Response\ResponseProvider;
use Resta\Contracts\HandleContracts;
use Resta\Support\ClosureDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Resta\Foundation\Bootstrapper\BootLoader;
use Resta\Container\ContainerInstanceResolver;

class ApplicationBaseRegister extends ApplicationProvider implements HandleContracts
{
    /**
     * check store directory
     *
     * @return void|mixed
     */
    private function isAvailableStore()
    {
        // if the store directory is available,
        // then the application process continues.
        // if not available, only the core is executed.
        if(file_exists(app()->path()->storeDir())){
            $this->app->register('isAvailableStore',true);
        }
        else{
            $this->app->register('isAvailableStore',false);
        }
    }

    /**
     * application pre loading
     *
     * @return mixed|void
     *
     */
    public function handle()
    {
        //we can use this method to move an instance of the application class with the kernel object
        //and easily resolve an encrypted instance of all the kernel variables in our helper class.
        ClassAliasGroup::setAlias(App::class,'application');

        //set base instances
        $this->setBaseInstances();

        //we define the general application instance object.
        define('appInstance',(base64_encode(serialize($this->app))));

        //main loader for application
        $this->mainLoader();

        //control of required store classes.
        $this->isAvailableStore();

        //global accessor handling
       $this->setGlobalAccessor();

        // sets a user-defined error handler function
        // this function can be used for defining your own way of handling errors during runtime,
        // for example in applications in which you need to do cleanup of data/files when a critical error happens,
        // or when you need to trigger an error under certain conditions (using trigger_error()).
        if($this->app['isAvailableStore']){
            $this->app->make('exception',function(){
                return ErrorProvider::class;
            });
        }
    }

    /**
     * set main loader
     *
     * @return void
     */
    private function mainLoader()
    {
        // for revision records,
        // the master key is assigned as revision.
        $this->app->register('revision',[]);

        //we're saving the directory where kernel files are running to the kernel object.
        $this->app->register('corePath',str_replace('Foundation','',__DIR__.''));

        //For the application, we create the object that the register method,
        // which is the container center, is connected to by the kernel object register method.
        $this->app->register('container',$this->app);

        // We are saving the application class to
        // the container object for the appClass value.
        $this->app->register('appClass',new App());

        //set closure bind instance for application
        $this->app->register('appClosureInstance',ClosureDispatcher::bind(app()));

        //set closure bind instance for bootLoader class
        $this->app->register('closureBootLoader',ClosureDispatcher::bind($this->app['bootLoader']));

        //set register for macro
        $this->app->register('macro',$this->app->resolve(Macro::class));

        //set register for macro
        $this->app->register('pipeline',new Pipeline());

    }

    /**
     * set base instances
     *
     * @return void|mixed
     */
    private function setBaseInstances()
    {
        //register as instance application object
        // and container instance resolve
        //set core instance value
        $this->app->instance('container',$this->app->singleton());
        $this->app->instance('bootLoader',$this->app->resolve(BootLoader::class));
        $this->app->instance('containerInstanceResolve',ContainerInstanceResolver::class);
        $this->app->instance('reflection',ReflectionProcess::class);
    }

    /**
     * @return void
     */
    private function setGlobalAccessor()
    {
        //get response success and status
        $this->app->register('instanceController',null);
        $this->app->register('responseSuccess',true);
        $this->app->register('responseStatus',200);
        $this->app->register('responseType','json');

        //we first load the response class as a singleton object to allow you to send output anywhere
        $this->app->register('out',$this->app->resolve(ResponseProvider::class));

        $requestService = "Store\Services\RequestService";

        //The HttpFoundation component defines an object-oriented layer for the HTTP specification.
        //The HttpFoundation component replaces these default PHP global variables and functions by an object-oriented layer
        if(Utils::isNamespaceExists($requestService)){

            Request::setFactory(function(array $query = array(),
                                         array $request = array(),
                                         array $attributes = array(),
                                         array $cookies = array(),
                                         array $files = array(),
                                         array $server = array(),
                                         $content = null) use ($requestService)
            {
                return new $requestService($query,
                    $request,
                    $attributes,
                    $cookies,
                    $files,
                    $server,
                    $content);
            });
        }


        //After registering the symfony request method, we also save the get and post methods for user convenience.
        $this->app->register('request',Request::createFromGlobals());
        $this->app->register('get',core()->request->query->all());
        $this->app->register('post',core()->request->request->all());

        //We determine with the kernel object which HTTP method the requested from the client
        $this->app->register('httpMethod',ucfirst(strtolower(core()->request->getRealMethod())));

        define('httpMethod',strtoupper(core()->httpMethod));

        $this->app->register('fileSystem',new FileProcess());
    }

}