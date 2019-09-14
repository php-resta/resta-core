<?php

namespace Resta\Client;

use Resta\Contracts\ApplicationContracts;
use Resta\Foundation\ApplicationProvider;

class ClientHttpManager extends ApplicationProvider
{
    /**
     * @var string $method
     */
    protected $method;

    /**
     * @var object
     */
    protected $client;

    /**
     * ClientHttpManager constructor.
     * @param ApplicationContracts $app
     * @param object $client
     */
    public function __construct(ApplicationContracts $app, $client)
    {
        parent::__construct($app);

        $this->client = $client;

        $this->method = httpMethod();
    }

    /**
     * http data resolve
     *
     * @return mixed
     */
    public function resolve()
    {
        $inputs = $this->app->get($this->method);

        $content = json_decode($this->app['request']->getContent(),1);

        if(is_array($inputs) && count($inputs)){

            if(isset($inputs[$this->client->getClientName()])){
                return $inputs[$this->client->getClientName()];
            }
            return $inputs;
        }

        if(is_array($content)){

            if(isset($content[$this->client->getClientName()])){
                return $content[$this->client->getClientName()];
            }
            return $content;
        }

        return [];
    }

    /**
     * get http method
     *
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

}