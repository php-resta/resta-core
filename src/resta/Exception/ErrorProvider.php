<?php

namespace Resta\Exception;

use Resta\Support\Str;
use Resta\Support\Dependencies;
use Resta\Support\ClosureDispatcher;
use Resta\Foundation\ApplicationProvider;
use Resta\Foundation\PathManager\StaticPathModel;

class ErrorProvider extends ApplicationProvider
{
    /**
     * @var null|string
     */
    protected $lang;

    /**
     * @var null|object
     */
    protected $exception;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var array
     */
    protected $result = [];

    /**
     * get status according to exception trace
     *
     * @return void
     */
    private function getStatusAccordingToExceptionTrace()
    {
        if($this->app->has('exceptiontrace')) {
            $this->data['status'] = (int)$this->app['exceptiontrace']['callNamespace']->getCode();
        }
        else {
            $this->data['status'] = (int)$this->exception::exceptionTypeCodes($this->data['errType']);
        }
    }

    /**
     * @return void|mixed
     */
    private function getStatusFromContext()
    {
        $this->getStatusAccordingToExceptionTrace();

        $this->app->terminate('responseSuccess');
        $this->app->terminate('responseStatus');
        $this->app->register('responseSuccess',(bool)false);
        $this->app->register('responseStatus',$this->data['status']);


        $optionalException = str_replace("\\","\\\\",$this->app->namespace()->exception());

        if(preg_match('@'.$optionalException.'@is',$this->data['errType'])){

            //trace pattern
            $trace = $this->data['errContext']['trace'];
            if(preg_match('@Stack trace:\n#0(.*)\n#1@is',$trace,$traceArray)){

                $traceFile = str_replace(root,'',$traceArray[1]);

                if(preg_match('@(.*)\((\d+)\)@is',$traceFile,$traceResolve)){
                    $this->data['errFile'] = $traceResolve[1];
                    $this->data['errLine'] = (int)$traceResolve[2];
                }
            }


            $this->data['errType'] = class_basename($this->data['errType']);
        }

        if(is_array($meta = config('response.meta'))){

            //set as the success object is false
            $this->data['appExceptionSuccess'] = [];
        }
        else{

            //set as the success object is false
            $this->data['appExceptionSuccess'] = ['success'=>(bool)false,'status'=>$this->data['status']];
        }
    }

    /**
     * error provider handle
     *
     * @return void
     */
    public function handle()
    {
        //sets which php errors are reported
        error_reporting(0);

        // in general we will use the exception class
        // in the store/config directory to make it possible
        // to change the user-based exceptions.
        $this->exception = StaticPathModel::$store.'\Config\Exception';

        //This function can be used for defining your own way of handling errors during runtime,
        //for example in applications in which you need to do cleanup of data/files when a critical error happens,
        //or when you need to trigger an error under certain conditions (using trigger_error()).
        set_error_handler([$this,'setErrorHandler']);

        //Registers a callback to be executed after script execution finishes or exit() is called.
        //Multiple calls to register_shutdown_function() can be made, and each will be called in the same order as
        //they were registered. If you call exit() within one registered shutdown function,
        //processing will stop completely and no other registered shutdown functions will be called.
        register_shutdown_function([$this,'fatalErrorShutdownHandler']);
    }

    /**
     * set error handler
     *
     * @param null|string $errNo
     * @param null|string $errStr
     * @param null|string $errFile
     * @param null|string $errLine
     * @param null|string $errContext
     */
    public function setErrorHandler($errNo=null, $errStr=null, $errFile=null, $errLine=null, $errContext=null)
    {
        // in case of a deficiency,
        // we need to boot our general needs to be needed for the exception.
        Dependencies::loadBootstrapperNeedsForException();

        // in general we will use the exception class
        // in the store/config directory to make it possible
        // to change the user-based exceptions.
        $this->data['exception']            = $this->exception;
        $this->data['errType']              = 'Undefined';
        $this->data['errStrReal']           = $errStr;
        $this->data['errorClassNamespace']  = null;
        $this->data['errFile']              = $errFile;
        $this->data['errLine']              = $errLine;
        $this->data['errNo']                = $errNo;

        // catch exception via preg match
        // and then clear the Uncaught statement from inside.
        $this->getUncaughtProcess();

        //get status from context
        $this->getStatusFromContext();

        //get lang message for exception
        $this->getLangMessageForException();

        if($this->app->has('exceptiontrace')){

            $customExceptionTrace   = $this->app['exceptiontrace'];
            $this->data['errFile']  = $customExceptionTrace['file'];
            $this->data['errLine']  = $customExceptionTrace['line'];
        }

        $environment = $this->app->environment();

        $vendorDirectory = str_replace(root.''.DIRECTORY_SEPARATOR.'','',$this->data['errFile']);

        if(Str::startsWith($vendorDirectory,'vendor')
            && Str::startsWith($vendorDirectory,'vendor/php-resta')===false)
        {
            $externalMessage = ($environment==="production") ?
                'An unexpected external error has occurred' :
                $this->data['errStrReal'];

            $this->result = $this->getAppException($environment,$externalMessage);


            //Get or Set the HTTP response code
            http_response_code(500);
            $this->app->terminate('responseStatus');
            $this->app->register('responseStatus',500);


        }
        else{

            $this->result = $this->getAppException($environment,$this->data['errStrReal']);

            //Get or Set the HTTP response code
            http_response_code($this->data['status']);
        }

        if($environment==="production"){

            $productionLogMessage = $this->getAppException('local',$this->data['errStrReal']);
            $this->app->register('productionLogMessage',$this->app->get('out')->outputFormatter($productionLogMessage));
        }

        // exception extender The exception information
        // that is presented as an extra to the exception result set.
        $this->result = $this->getExceptionExtender();


        //set json app exception
        $this->app->register('routerResult',$this->result);

        $restaOutHandle = null;

        if(!defined('responseApp')){
            $restaOutHandle = $this->app->get('out')->handle();
        }

        if($restaOutHandle===null){

            //header set and symfony response call
            header('Content-type:application/json;charset=utf-8');

            echo json_encode($this->app->get('out')->outputFormatter($this->result));
            exit();
        }
        else{
            echo $restaOutHandle;
            exit();
        }
    }

    /**
     * @param $environment
     * @return mixed
     */
    private function getAppException($environment,$message)
    {
        return $this->data['appExceptionSuccess']+$this->data['exception']::$environment(
                $this->data['errNo'],
                $message,
                $this->data['errFile'],
                $this->data['errLine'],
                $this->data['errType'],
                $this->data['lang']
            );
    }

    /**
     * get exception extender object
     *
     * @return mixed
     */
    private function getExceptionExtender()
    {
        return $this->app->resolve(
            $this->app->get('macro')->call('exceptionExtender',function(){
                return ExceptionExtender::class;
            }),
            ['result'=>$this->result])->getResult();
    }

    /**
     * fatal error shutdown handler
     *
     * @return mixed
     */
    public function fatalErrorShutdownHandler()
    {
        //get fatal error
        $last_error = error_get_last();

        $this->inStackTrace($last_error);

        if(!is_null($last_error)){

            if(!defined('methodName')){
                define('methodName',null);
            }

            if($this->app->has('exceptionFile')){
                $last_error['file'] = $this->app['exceptionFile'];
                $last_error['line'] = $this->app['exceptionLine'];
            }

            $this->setErrorHandler(
                E_ERROR,
                $last_error['message'],
                $last_error['file'],
                $last_error['line'],
                []
            );
        }
    }

    /**
     * @param $error
     */
    public function inStackTrace($error)
    {
        if($this->app->has('urlComponent')){
            if(!preg_match('@'.$this->app['urlComponent']['project'].'@',$error['file'])
                && !$this->app->has('exceptionFile')){
                if(preg_match('@ in\s(.*?)\n@is',$error['message'],$result)){
                    $errorMessage = explode(":",$result[1]);
                    $this->app->register('exceptionFile',$errorMessage[0]);
                    $this->app->register('exceptionLine',$errorMessage[1]);
                }
            }
        }
    }

    /**
     * @return void|mixed
     */
    private function getLangMessageForException()
    {
        $clone = clone $this;

        if($this->app->has('exceptionTranslate')){

            $langMessage = trans('exception.'.$this->app->get('exceptionTranslate'));

            if(!is_null($langMessage) && $this->app->has('exceptionTranslateParams')){

                if(count($this->app['exceptionTranslateParams'][$this->app['exceptionTranslate']])){
                    foreach ($this->app['exceptionTranslateParams'][$this->app['exceptionTranslate']] as $key=>$value){

                        $valueLangName = !is_null(trans('default.'.$value)) ? trans('default.'.$value) : $value;
                        $langMessage = preg_replace('@\('.$key.'\)@is',$valueLangName,$langMessage);
                    }
                }
            }

            if($langMessage!==null){
                $this->data['errStrReal'] = $langMessage;
            }
        }

        if(class_exists($this->data['errorClassNamespace'])
            &&
            (Str::startsWith($this->data['errorClassNamespace'],'App')
                || Str::startsWith($this->data['errorClassNamespace'],__NAMESPACE__))){

            ClosureDispatcher::bind($this->data['errorClassNamespace'])->call(function() use ($clone) {
                if(property_exists($this,'lang')){
                    $clone->setLang($this->lang);
                }
            });
        }

        $this->data['lang'] = $clone->lang;

        $langMessage = (!is_null($this->data['lang'])) ? trans('exception.'.$this->data['lang']) : null;

        if($langMessage!==null){
            $this->data['errStrReal'] = $langMessage;
        }
    }

    /**
     * get uncaught process
     *
     * @return void|mixed
     */
    private function getUncaughtProcess()
    {
        // catch exception via preg match
        // and then clear the Uncaught statement from inside.
        if(preg_match('@(.*?):@is',$this->data['errStrReal'],$errArr)){

            $this->data['errType'] = trim(str_replace('Uncaught','',$errArr[1]));
            $this->data['errorClassNamespace'] = $this->data['errType'];
        }

        if(preg_match('@Uncaught@is',$this->data['errStrReal'])
            && preg_match('@(.*?):(.*?)\sin\s@is',$this->data['errStrReal'],$errStrRealArray)){
            $this->data['errStrReal'] = trim($errStrRealArray[2]);
        }

        $this->data['errContext']['trace'] = $this->data['errStrReal'];
    }

    /**
     * get result for exception
     *
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param null|string $lang
     */
    public function setLang($lang=null)
    {
        $this->lang = $lang;
    }

}