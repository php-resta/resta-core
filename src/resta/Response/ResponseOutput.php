<?php

namespace Resta\Response;

use Resta\Config\ConfigProcess;
use Resta\Router\KernelRouterProcess;

class ResponseOutput
{
    /**
     * @var $printer
     */
    private $printer;

    /**
     * @param $printer
     * @return array
     */
    private function dataIncludedForPrinter($printer)
    {
        if(isset(core()->controllerWatch)){

            $watch=core()->controllerWatch;
            return array_merge($printer,['watch'=>['memory'=>$watch['memory']]]);
        }

        //return printer
        return $printer;
    }

    /**
     * @return mixed
     */
    private function getRouter()
    {
        return app()->resolve(KernelRouterProcess::class)->router();
    }

    /**
     * @return array
     */
    protected function getOutPutter()
    {
        return $this->printer($this->getRouter());
    }

    /**
     * @param array $data
     * @return array
     */
    private function hateoasCapsule($data=array())
    {
        return (config('app.hateoas')) ? array_merge($data,config('hateoas')) : $data;
    }

    /**
     * @return mixed
     */
    private function metaAdd()
    {
        return config('response.meta');
    }

    /**
     * @param $output
     * @param callable $callback
     * @return mixed
     */
    private function noInExceptionHateoas($output,callable $callback)
    {
        if(isset($output['success']) && false===$output['success']){
            return $output;
        }
        return call_user_func($callback);
    }

    /**
     * @param $data
     * @return array
     */
    private function outputCapsule($data)
    {
        $configResponseData = config('response.data');

        return $this->hateoasCapsule([
            $configResponseData => $data,
        ]);
    }

    /**
     * @param $output
     * @return array|mixed
     */
    private function printer($output)
    {
        //if the system throws an exception,
        //we subtract the hateoas extension from the output value.
        $this->printer = $this->noInExceptionHateoas($output,function() use ($output){

            return array_merge(
                $this->metaAdd(),
                $this->outputCapsule($output)
            );
        });

        // For the data to be included in the response,
        // we go to the dataIncludedForPrinter method.
        $this->printer=$this->dataIncludedForPrinter($this->printer);

        // If the log feature is available on the kernel,
        // we run the logger process.
        if(isset(core()->log)){

            // we can run logging after checking
            // the configuration for the logger process in the LoggerService class
            // so that,If logging is not allowed in the main configuration file, we will not log.
            return core()->loggerService->checkLoggerConfiguration($this->printer,function($printer){
                return $printer;
            });
        }

        //default printer
        return $this->printer;
    }
}