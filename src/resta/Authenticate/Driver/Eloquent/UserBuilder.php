<?php

namespace Resta\Authenticate\Driver\Eloquent;

use Resta\Authenticate\Driver\BuilderContract;

class UserBuilder implements BuilderContract {

    /**
     * @param \Resta\Authenticate\AuthenticateProvider $app
     */
    public function login($app,$credentials){

        //in addition to the default credentials values
        // ​​on the user side, a closure method is executed and an extra query occurs.
        $getAddToWhereClosure=$app->getAddToWhere();

        //we get the model specified for the builder.
        $driver=$app->getDriverNamespace();

        // using the driver object we write the query builder statement.
        // we do the values of the query with the credentials that are sent.
        $query=$driver::where(function($query) use($credentials,$getAddToWhereClosure) {

            // with the callback method (eloquent model)
            // we write the where clause.
            foreach ($credentials as $credential=>$credentialValue){
                $query->where($credential,$credentialValue);
            }

            // if the addToWhereClosure value is a closure,
            // then in this case we actually run
            // the closure object and add it to the query value.
            if(is_callable($getAddToWhereClosure)){
                $getAddToWhereClosure($query);
            }
        });

        // with query we bind the returned values to the params property of the auth object.
        // and so the auth object will make a final return with these values.
        $app->params['type']        = 'login';
        $app->params['builder']     = $query;
        $app->params['status']      = $query->count();

        // when the query succeeds,
        // we update the token value.
        $this->updateToken($app);
    }

    /**
     * @param \Resta\Authenticate\AuthenticateProvider $app
     */
    private function updateToken($app){

        //if query status value is true
        if($app->params['status']){

            // we go to the method that produces
            // the classical token value and get the token value.
            $app->params['token']=$app->getTokenData();

            // we update the token value.
            // if there is no update, we reset the status value to 0.
            $update=$app->params['builder']->update(['token'=>$app->params['token']]);
            if(!$update){
                $app->params['status']=0;
            }
        }
    }
}
