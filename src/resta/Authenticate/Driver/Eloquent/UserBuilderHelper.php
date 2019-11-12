<?php

namespace Resta\Authenticate\Driver\Eloquent;

use Resta\Authenticate\Resource\AuthLoginCredentialsManager;
use Resta\Authenticate\Resource\AuthUserManager;
use Resta\Support\Arr;

class UserBuilderHelper
{
    /**
     * @var array
     */
    protected $query = [];

    /**
     * @var null|array
     */
    protected $credentials;

    /**
     * @var bool
     */
    protected $passwordVerify = false;

    /**
     * @var $password
     */
    private $password;

    /**
     * UserBuilderHelper constructor.
     */
    public function __construct()
    {
        //in addition to the default credentials values
        // ​​on the user side, a closure method is executed and an extra query occurs.
        $this->query['addToWhere'] = $this->auth->getAddToWhere();

        //we get the model specified for the builder.
        $this->query['driver'] = $this->auth->getDriverNamespace();
    }

    /**
     * get all device token query
     *
     * @param AuthUserManager $manager
     * @return mixed
     */
    protected function allDeviceTokenQuery($manager)
    {
        $userId = $manager->getAuth()->params['userId'];

        return DeviceToken::where('user_id',$userId)->get();
    }

    /**
     * @param $token
     * @return mixed
     */
    protected function checkQuery($token)
    {
        //token query for builder
        return DeviceToken::where(function($query) use($token) {

            //where query for token
            $query->where('token_integer',crc32(md5($token)));
            $query->where('device_agent_integer',crc32(md5($_SERVER['HTTP_USER_AGENT'])));
        });
    }

    /**
     * @param $token
     * @return mixed|void
     */
    protected function logoutQuery($token)
    {
        //token query for builder
        return DeviceToken::where(function($query) use($token) {

            //where query for token
            $query->where('token_integer',crc32(md5($token)));
            $query->where('device_agent_integer',crc32(md5($_SERVER['HTTP_USER_AGENT'])));

        });
    }

    /**
     * check pasword verify
     *
     * @param null|object $query
     * @return mixed
     */
    protected function checkPasswordVerify($query=null)
    {
        if(is_null($query) && isset($this->credentials['password'])){
            if(!is_null($password = $this->auth->provider('password'))
                && $password($this->credentials['password'])=='verify'){

                $this->password = $this->credentials['password'];
                $this->passwordVerify = true;
                $this->credentials = Arr::removeKey($this->credentials,['password']);

                return null;
            }
        }

        if(is_object($query) && $query->count()){
            $password = $query->first()->password;
            if(password_verify($this->password,$password)){
                return $query;
            }
        }

        return null;
    }

    /**
     * set query
     *
     * @param AuthLoginCredentialsManager $credentials
     * @return mixed
     */
    protected function setQuery($credentials)
    {
        //we get the model specified for the builder.
        $driver = $this->query['driver'];

        //get query credentials
        $this->credentials = $credentials->get();

        $this->checkPasswordVerify();

        // using the driver object we write the query builder statement.
        // we do the values of the query with the credentials that are sent.
        $query = $driver::where(function($query) use($credentials) {

            // with the callback method (eloquent model)
            // we write the where clause.
            foreach ($this->credentials as $credential=>$credentialValue){

                if(!is_null($provider = $this->auth->provider($credential))){
                    $query->where($credential,$provider($credentialValue));
                }
                else{
                    $query->where($credential,$credentialValue);
                }
            }

            // for the authenticate query,
            // the user can add additional queries by the service provider.
            if(!is_null($addQuery = $this->auth->provider('addQuery'))){
                $addQuery($query);
            }
        });

        if(false === $this->passwordVerify){
            return $query;
        }

        return $this->checkPasswordVerify($query);
    }

    /**
     * @return void|mixed
     */
    protected function updateToken($token=null)
    {
        //if query status value is true
        if($this->auth->params['status']){

            // we go to the method that produces
            // the classical token value and get the token value.
            $this->auth->params['token'] = ($token===null) ? $this->auth->getTokenData() : $token;

            // we update the token value.
            // if there is no update, we reset the status value to 0.
            $update = $this->auth->params['builder']->update(['token'=>$this->auth->params['token']]);

            if(!$update){
                $this->auth->params['status'] = 0;
                $this->auth->params['exception'] = 'update';
            }
            else{
                if(!is_null($after = $this->auth->provider('after'))){
                    if(false === $after($this->auth->params['builder']->first())){
                        $this->auth->params['status'] = 0;
                        $this->auth->params['exception'] = 'update';
                    }
                }
            }


        }
    }

    /**
     * save device token for token
     *
     * @return mixed
     */
    protected function saveDeviceToken()
    {
        $token_integer = crc32(md5($this->auth->params['token']));

        if(!is_null($token_integer)){

            if(DeviceToken::where('user_id',$this->auth->params['authId'])
                    ->where('device_agent_integer',crc32(md5($_SERVER['HTTP_USER_AGENT'])))->count()==0){

                return DeviceToken::create([
                    'user_id' => $this->auth->params['authId'],
                    'token' => $this->auth->params['token'],
                    'token_integer' => $token_integer,
                    'device_agent' => $_SERVER['HTTP_USER_AGENT'],
                    'device_agent_integer' => crc32(md5($_SERVER['HTTP_USER_AGENT'])),
                    'expire' => $this->auth->getExpire(),
                ]);
            }
            else{

                return DeviceToken::where('user_id',$this->auth->params['authId'])
                    ->where('device_agent_integer',crc32(md5($_SERVER['HTTP_USER_AGENT'])))
                    ->update([
                        'token' => $this->auth->params['token'],
                        'token_integer' => $token_integer
                    ]);
            }

        }

    }

    /**
     * delete device token for token
     *
     * @return mixed|void
     */
    protected function deleteDeviceToken()
    {
        $token_integer = crc32(md5($this->auth->getTokenSentByUser()));

        if(!is_null($token_integer)){

            DeviceToken::where('token_integer',$token_integer)->delete();

            return (DeviceToken::where('token_integer',$token_integer)->count()) ? false : true;
        }

    }

    /**
     * @param AuthUserManager $manager
     * @return mixed
     */
    protected function userProcessQuery($manager)
    {
        $userId = $manager->getAuth()->params['userId'];
        $namespace = $manager->getAuth()->getDriverNamespace();

        return $namespace::find($userId);
    }
}
