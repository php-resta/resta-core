<?php

namespace Resta\Support;

class ClassAliasGroup
{
    /**
     * @return mixed
     */
   public function handle()
   {
       return $this->getConfigAliasGroup();
   }

    /**
     * @return array
     */
   public function getConfigAliasGroup()
   {
       $aliasGroup=app()->namespace()->config().'\AliasGroup';
       if(class_exists($aliasGroup)){
           return app()->makeBind($aliasGroup)->handle();
       }
       return [];
   }

    /**
     * @param $object
     * @param $name
     * @return void
     */
   public static function setAlias($object,$name)
   {
       if((new $object) instanceof  $name === false){
           class_alias($object,$name);
       }
   }
}