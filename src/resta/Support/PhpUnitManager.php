<?php

namespace Resta\Support;

class PhpUnitManager
{
    /**
     * @var array
     */
    protected $data;

    /**
     * PhpUnitManager constructor.
     * @param array $data
     */
    public function __construct($data=array())
    {
        $this->data = $data;
    }

    /**
     * add new element for phpunit.xml
     *
     * @param null|string $attribute
     * @param null|string $key
     * @param null|string $value
     * @return mixed
     */
    public function add($attribute=null,$key=null,$value=null)
    {
        $list = [];

        //all add method parameters must come full.
        if(!is_null($attribute) && !is_null($key) && !is_null($value)){

            // we do 0 key control for the testsuite data.
            // this phpunit has a multiple test suite data if 0 key is present.
            if(isset($this->data['testsuites']['testsuite'][0])){
                foreach ($this->data['testsuites']['testsuite'] as $key=>$item){
                    $list[$key] = $item;
                }
            }
            else{
                //only for a single test suite data
                $list[] = $this->data['testsuites']['testsuite'];
            }

            // the data to be added is started with
            // the number of pieces of the previous test suite.
            $newKey = count($list);

            //add new data
            $list[$newKey]['@attributes']['name'] = $attribute;
            $list[$newKey][$key] = $value;
        }

        return $list;
    }
}