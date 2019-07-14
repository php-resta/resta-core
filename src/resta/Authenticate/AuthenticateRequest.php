<?php

namespace Resta\Authenticate;

use Resta\Authenticate\Resource\AuthLoginCredentialsManager;
use Resta\Request\Request as RequestClient;

class AuthenticateRequest extends RequestClient
{
    /**
     * With the auto injector,
     * all the indices in the array are executed as methods
     * and the global input is automatically injected.
     *
     * @var array $autoInject
     */
    protected $autoInject = [];

    /**
     * The values ​​expected by the server.
     *
     * @var array
     */
    protected $expected = [];

    /**
     * mandatory http method.
     *
     * @var array
     */
    protected $http = [];

    /**
     * @var null|object
     */
    protected $credentials;

    /**
     * AuthenticateRequest constructor.
     * @param AuthLoginCredentialsManager $credentials
     */
    public function __construct($credentials)
    {
        $this->credentials = $credentials;

        //credentials loop for expected property
        foreach ($this->credentials->get() as $credential){
            $this->expected[] = $credential;
        }

        parent::__construct();
    }

    public function all()
    {
        return $this->inputs;
    }

    /**
     * @var string
     */
    protected $password;

    /**
     * @return string
     */
    protected function password()
    {
        return md5(sha1($this->password));
    }
}