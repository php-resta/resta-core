<?php

namespace Resta\Authenticate\Driver;

trait BuilderParamGenerator
{
    /**
     * get param values for query basic
     *
     * @param $type
     * @param $query
     */
    protected function paramValues(...$params)
    {
        [$type,$query] = $params;

        // with query we bind the returned values to the params property of the auth object.
        // and so the auth object will make a final return with these values.
        $this->auth->params['type']        = $type;
        $this->auth->params['builder']     = $query;
        $this->auth->params['status']      = ($query===null) ? 0 : $query->count();

        // if status is true,
        // we add values ​​for some user benefits to params global access.
        if($this->auth->params['status']){

            $this->auth->params['auth']        = $query->get();
            $this->auth->params['data']        = $query->first();
            $this->auth->params['authId']      = $query->first()->id;
            $this->auth->params['authToken']   = $query->first()->token;
        }

    }


}