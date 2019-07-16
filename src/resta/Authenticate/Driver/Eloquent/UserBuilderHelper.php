<?php

namespace Resta\Authenticate\Driver\Eloquent;

use Resta\Authenticate\Resource\AuthLoginCredentialsManager;
use Resta\Authenticate\Resource\AuthUserManager;

class UserBuilderHelper
{
    /**
     * @var array
     */
    protected $query = [];

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
     * @param null|object
     * @return mixed
     */
    protected function callbackQueryWithoutCredentials($driver)
    {
        if($this->isCallableAddToWhere()){

            return $driver::where(function($query) {

                // if the addToWhereClosure value is a closure,
                // then in this case we actually run
                // the closure object and add it to the query value.
                $this->queryAddToWhere($query);
            });
        }
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

            // if the addToWhereClosure value is a closure,
            // then in this case we actually run
            // the closure object and add it to the query value.
            $this->queryAddToWhere($query);
        });
    }

    /**
     * @return bool
     */
    protected function isCallableAddToWhere()
    {
        // addToWhere checks whether
        // the config value is a callable value.
        return is_callable($this->query['addToWhere']);
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

            // if the addToWhereClosure value is a closure,
            // then in this case we actually run
            // the closure object and add it to the query value.
            $this->queryAddToWhere($query);

        });
    }

    /**
     * get query add to where
     *
     * @param $query
     * @param array $credentials
     * @return mixed
     */
    protected function queryAddToWhere($query,$credentials=array())
    {
        // if the addToWhereClosure value is a closure,
        // then in this case we actually run
        // the closure object and add it to the query value.
        if($this->isCallableAddToWhere()){
            return $this->query['addToWhere']($query,$credentials);
        }
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

        if(count($credentials->get())==0){

            // if the credential array is empty in the config section,
            // then you must run the query with a callable value of addToWhere value.
            return $this->callbackQueryWithoutCredentials($driver);
        }

        //
        if($this->isCallableAddToWhere()){
            return $this->queryAddToWhere($driver,$credentials->get());
        }

        // using the driver object we write the query builder statement.
        // we do the values of the query with the credentials that are sent.
        return $driver::where(function($query) use($credentials) {

            // with the callback method (eloquent model)
            // we write the where clause.
            foreach ($credentials->get() as $credential=>$credentialValue){
                $query->where($credential,$credentialValue);
            }

            // if the addToWhereClosure value is a closure,
            // then in this case we actually run
            // the closure object and add it to the query value.
            $this->queryAddToWhere($query,$credentials->get(),$credentials->get());
        });
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
     * @return mixed
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
