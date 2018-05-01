<?php

namespace Resta;

/**
 * Class ClassAliasGroup
 * @package Resta
 */
class ClassAliasGroup {

    /**
     * @param $class
     * @return mixed
     */
   public function handle($class){

       $list=[];
       $aliasGroup=$this->getConfigAliasGroup();
       if(count($aliasGroup)){
           foreach ($aliasGroup as $aliasKey=>$aliasValue){
               $list[$aliasKey]=$aliasGroup[$aliasKey];
           }
       }
       return $list;

   }

    /**
     * @return mixed
     */
   public function getConfigAliasGroup(){

       $aliasGroup=app()->namespace()->config().'\AliasGroup';
       if(class_exists($aliasGroup)){
           return $aliasGroupInstance=app()->makeBind($aliasGroup)->handle();
       }
       return [];

   }
}