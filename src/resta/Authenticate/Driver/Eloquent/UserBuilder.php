<?php

namespace Resta\Authenticate\Driver\Eloquent;

use Resta\Authenticate\Driver\BuilderContract;

class UserBuilder implements BuilderContract {

    /**
     * @param \Resta\Authenticate\AuthenticateProvider $app
     */
    public function login($app,$credentials){

        //we get the model specified for the builder.
        $driver=$app->getDriverNamespace();

        // using the driver object we write the query builder statement.
        // we do the values of the query with the credentials that are sent.
        $query=$driver::where(function($query) use($credentials) {

            // with the callback method (eloquent model)
            // we write the where clause.
            foreach ($credentials as $credential=>$credentialValue){
                $query->where($credential,$credentialValue);
            }

        });

        // with query we bind the returned values to the params property of the auth object.
        // and so the auth object will make a final return with these values.
        $app->params['loginBuilder'] = $query;
        $app->params['loginStatus']  = $query->count();
    }
}
