<?php

namespace Resta\Authenticate\Driver\Eloquent;

class UserBuilderHelper
{
    /**
     * @var array
     */
    protected $query=[];

    /**
     * UserBuilderHelper constructor.
     */
    public function __construct()
    {
        //in addition to the default credentials values
        // ​​on the user side, a closure method is executed and an extra query occurs.
        $this->query['addToWhere']=$this->auth->getAddToWhere();

        //we get the model specified for the builder.
        $this->query['driver']=$this->auth->getDriverNamespace();
    }

    /**
     * @param $driver
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
        //we get the model specified for the builder.
        $driver=$this->query['driver'];

        //token query for builder
        return $driver::where(function($query) use($token) {

            //where query for token
            $query->where('token',$token);

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
     * @return mixed
     */
    protected function logoutQuery($token)
    {
        //we get the model specified for the builder.
        $driver=$this->query['driver'];

        //token query for builder
        $query=$driver::where(function($query) use($token) {

            //where query for token
            $query->where('token',$token);

            // if the addToWhereClosure value is a closure,
            // then in this case we actually run
            // the closure object and add it to the query value.
            $this->queryAddToWhere($query);

        });

        return $query;
    }

    /**
     * @param $query
     */
    protected function queryAddToWhere($query)
    {
        // if the addToWhereClosure value is a closure,
        // then in this case we actually run
        // the closure object and add it to the query value.
        if($this->isCallableAddToWhere()){
            $this->query['addToWhere']($query);
        }
    }

    /**
     * @param $credentials
     * @return mixed
     */
    protected function setQuery($credentials)
    {
        //we get the model specified for the builder.
        $driver=$this->query['driver'];

        if(count($credentials->get())==0){

            // if the credential array is empty in the config section,
            // then you must run the query with a callable value of addToWhere value.
            return $this->callbackQueryWithoutCredentials($driver);
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
            $this->queryAddToWhere($query);
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
            $this->auth->params['token']=($token===null) ? $this->auth->getTokenData() : $token;

            // we update the token value.
            // if there is no update, we reset the status value to 0.
            $update=$this->auth->params['builder']->update(['token'=>$this->auth->params['token']]);

            if(!$update){
                $this->auth->params['status']=0;
                $this->auth->params['exception']='update';
            }
        }
    }
}
