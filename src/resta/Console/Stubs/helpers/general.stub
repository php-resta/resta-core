<?php

use Resta\Contracts\ClientContract;

if (!function_exists('clientArray')) {

    /**
     * @param ClientContract $clientContract
     * @param null $criteria
     * @return array
     */
    function clientArray(ClientContract $clientContract,$criteria=null)
    {
        $all = $clientContract->all();

        $list = [];

        if(!is_null($criteria) && isset($all[$criteria]) && is_array($all[$criteria])){
            foreach ($all as $input=>$item){
                if(is_array($item)){
                    foreach ($item as $key=>$value){
                        $list[$key][$input] = $value;
                    }
                }
            }
        }


        return $list;
    }
}

if (!function_exists('mysqlErrorColumn')) {

    /**
     * @param null $error
     * @return mixed|void
     */
    function mysqlErrorColumn($error=NULL)
    {
        if(preg_match('@\'(.*?)\'@is',$error,$list)){
            if(isset($list[1])){
                return $list[1].' value is invalid for data format.';
            }
        }

        return 'Data format is invalid';
    }
}

if (!function_exists('nullExceptionPointer')) {

    /**
     * @param mixed $value
     * @param null|string|array $params
     * @param string $exception
     * @return mixed
     */
    function nullExceptionPointer($value,$params,$exception='invalidArgument')
    {
        if(is_null($value) || (is_array($value) && count($value)==0) ){

            if(is_string($params)){
                exception('nullExceptionPointer',['key'=>$params])->{$exception}('nullExceptionPointer');
            }

            if(is_array($params)){
                exception('nullExceptionPointer',$params)->{$exception}('nullExceptionPointer');
            }
        }

        return $value;
    }
}

if (!function_exists('clientRequestInputs')) {


    function clientRequestInputs()
    {
        if(app()->has('clientRequestInputs')){
            return app()->get('clientRequestInputs');
        }

        return [];
    }
}

if (!function_exists('arrayPosting')) {


    function arrayPosting()
    {
        $list = [];

        $post = post();

        foreach($post as $key=>$value){
            foreach ((array)$value as $valueKey=>$item){
                $list[$valueKey][$key] = $item;
            }
        }

        return $list;
    }
}

if (!function_exists('codeGenerator')) {

    /**
     * @param object $client
     * @param array $criteria
     * @return int
     */
    function codeGenerator($client,$criteria=[])
    {
        $criteriaProcess = [];

        foreach ($criteria as $criterion){
            $inputCriterion = $client->input($criterion);
            if(is_array($inputCriterion)){
                exception('notArrayForInput',[
                    'key' =>$criterion
                ])->invalidArgument('expected values codeGenerator Error');
            }
            $criteriaProcess[] = $client->input($criterion);
        }

        $codeString = implode('_',$criteriaProcess).'_'.time();
        unset($criteriaProcess);
        return crc32(md5($codeString));
    }
}

if (!function_exists('router')) {

    /**
     * @param null|string $key
     * @return void|mixed
     */
    function router($key=null)
    {
        if(app()->has('routing.current') && is_callable($current = app()->get('routing.current'))){
            $currentCallable = $current();

            if(is_null($key)){
                return $currentCallable;
            }
            return $currentCallable[$key];

        }

        exception('routingCurrentError')->runtime('routingCurrentError');
    }
}

if (!function_exists('checkFieldForTable')) {

    /**
     * @param $field
     * @param string $table
     * @return bool
     */
    function checkFieldForTable($field,$table)
    {
        $table = entities($table);

        return in_array($field,$table);
    }
}

if (!function_exists('objectFormat')) {

    /**
     * @param array $data
     * @return mixed
     */
    function objectFormat($data=array())
    {
        $list = [];

        foreach ($data as $key=>$array){
            if(is_numeric($key) && is_array($array)){
                foreach ($array as $arrayKey=>$arrayValue){
                    $list[$arrayKey][] =$arrayValue;
                }
            }
        }

        if(count($list)){
            return $list;
        }

        return $data;
    }
}

if (!function_exists('checkInput')) {

    /**
     * @param array $data
     * @return array|void
     */
    function checkInput($data=array())
    {
        if(count($data)){
            return $data;
        }

        exception()->noInput;
    }
}
