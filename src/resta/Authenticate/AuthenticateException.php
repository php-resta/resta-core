<?php

namespace Resta\Authenticate;

trait AuthenticateException
{
    /**
     * @return void|mixed
     */
    public function getExceptionForHttp($http)
    {
        exception('authenticateHttpException')->badMethodCall('Authenticate requests ['.$http.'] as http method');
    }

    /**
     * @return void|mixed
     */
    public function credentials()
    {
        exception('authenticateCredentialException')->invalidArgument('credentials fail for authenticate');
    }

    /**
     * @param $exceptionType
     */
    public function exceptionManager($exceptionType)
    {
        return $this->{$exceptionType}();
    }

    /**
     * logout exception
     *
     * @return mixed|void
     */
    public function logoutException()
    {
        exception('authenticateLogoutException')->runtime('no such token data is available.');
    }

    /**
     * logout exception
     *
     * @return mixed|void
     */
    public function logoutInternal()
    {
        exception('authenticateLogoutInternal')->runtime('You have failed to sign out. There was a problem with us. Please try again.');
    }

    /**
     * token exception
     *
     * @return mixed|void
     */
    public function tokenException()
    {
        exception('authenticateTokenException')->invalidArgument('Your token is missing for authenticate process');
    }

    /**
     * update exception
     *
     * @return mixed|void
     */
    public function update()
    {
        exception('authenticateUpdateException')->invalidArgument('Updating Token for authenticate is missing.');
    }
}