<?php

namespace Resta\Support;

use Resta\Contracts\ApplicationContracts;
use Resta\Foundation\ApplicationProvider;

class TrackLogger extends ApplicationProvider
{
    /**
     * @var null|array
     */
    protected $output;

    /**
     * @var null|array
     */
    protected $arguments;

    /**
     * TrackLogger constructor.
     * @param ApplicationContracts $app
     * @param $output
     * @param $arguments
     */
    public function __construct(ApplicationContracts $app,$output,$arguments)
    {
        parent::__construct($app);
        
        if(!$this->app->runningInConsole()){
            exception()->runtime('Console application is missing');
        }
        
        $this->output = $output;
        $this->arguments = $arguments;
    }

    /**
     * track logger handle
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->output['meta']['success'])
        {
            echo ''.$this->output['trackNumber'].' - SUCCESS:';
            echo PHP_EOL;
            echo 'Request Success : true';
        }
        else{

            echo ''.$this->output['trackNumber'].' - ERROR:';
            echo PHP_EOL;
            echo 'Error: '.$this->output['resource']['errorMessage'];
            echo PHP_EOL;
            echo 'Error File: '.$this->output['resource']['errorFile'];
            echo PHP_EOL;
            echo 'Error Line: '.$this->output['resource']['errorLine'];
            echo PHP_EOL;
            echo 'Error Type: '.$this->output['resource']['errorType'];
        }

        echo PHP_EOL;
        echo 'Request Code: '.$this->output['meta']['status'];

        echo PHP_EOL;
        echo 'Client Ip: '.isset($this->output['clientIp']) ? $this->output['clientIp'] : null ;

        echo PHP_EOL;
        echo 'Endpoint: '.isset($this->output['requestUrl']) ? $this->output['requestUrl'] : null;

        echo PHP_EOL;
        echo 'Get Data: '.json_encode(isset($this->output['getData']) ? $this->output['getData'] : []);

        echo PHP_EOL;
        echo 'Post Data: '.json_encode(isset($this->output['postData']) ? $this->output['postData'] : []);

        echo PHP_EOL;
        echo 'Auth: '.isset($this->output['auth']) ? $this->output['auth'] : null;

        echo PHP_EOL;
        echo 'Time: '.date('Y-m-d H:i:s');

        echo PHP_EOL;
        echo 'Client Key: '.isset($this->output['clientApiTokenKey']) ? $this->output['clientApiTokenKey'] : null;

        echo PHP_EOL;
        echo PHP_EOL;
    }
}