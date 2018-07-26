<?php

namespace Resta\Authenticate\Driver\Eloquent;

class UserBuilderHelper {

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
     * @param $token
     */
    protected function checkQuery($token){

        //we get the model specified for the builder.
        $driver=$this->query['driver'];

        //token query for builder
        return $driver::where('token',$token);

    }

    /**
     * @param $credentials
     * @return mixed
     */
    protected function setQuery($credentials){

        //we get the model specified for the builder.
        $driver=$this->query['driver'];

        // using the driver object we write the query builder statement.
        // we do the values of the query with the credentials that are sent.
        return $driver::where(function($query) use($credentials) {

            // with the callback method (eloquent model)
            // we write the where clause.
            foreach ($credentials as $credential=>$credentialValue){
                $query->where($credential,$credentialValue);
            }

            // if the addToWhereClosure value is a closure,
            // then in this case we actually run
            // the closure object and add it to the query value.
            if(is_callable($this->query['addToWhere'])){
                $this->query['addToWhere']($query);
            }
        });
    }

    /**
     * @return void|mixed
     */
    protected function updateToken(){

        //if query status value is true
        if($this->auth->params['status']){

            // we go to the method that produces
            // the classical token value and get the token value.
            $this->auth->params['token']=$this->auth->getTokenData();

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
