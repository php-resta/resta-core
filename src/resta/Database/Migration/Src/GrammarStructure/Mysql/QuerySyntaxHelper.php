<?php

namespace Migratio\GrammarStructure\Mysql;


class QuerySyntaxHelper
{
    /**
     * @param $nullable
     * @param $name
     * @return string
     */
    protected function getNullableValue($nullable,$name)
    {
        $nullableValue='';

        if(isset($nullable[$name])){
            if($nullable[$name]===false){
                $nullableValue='NOT NULL';
            }
            else{
                $nullableValue='NULL';
            }
        }

        return $nullableValue;
    }

    /**
     * @return void
     */
    protected function getCreateTableSyntax()
    {
        $this->syntax[] = 'CREATE TABLE '.$this->table.' (';
    }

    /**
     * @param $object
     */
    protected function getWizardObjects($object)
    {
        $this->data['names']            = $object->getNames();
        $this->data['types']            = $object->getTypes();
        $this->data['default']          = $object->getDefault();
        $this->data['autoIncrement']    = $object->getAutoIncrement();
        $this->data['primaryKey']       = $object->getPrimaryKey();
        $this->data['tableCollation']   = $object->getCollation();
        $this->data['engine']           = $object->getEngine();
        $this->data['nullable']         = $object->getNullable();
        $this->data['comment']          = $object->getComment();
        $this->data['unique']           = $object->getUnique();
        $this->data['index']            = $object->getIndex();
        $this->data['references']       = $object->getReferences();
    }

    /**
     * @param $name
     * @return array
     */
    protected function getValueWithIsset($name)
    {
        $nameKey = array_search($name,$this->data['names']);
        
        $list   = [];
        $list[] = $this->getNullableValue($this->data['nullable'],$name);
        $list[] = (isset($this->data['autoIncrement'][$name])) ? 'AUTO_INCREMENT' : '';
        $list[] = (isset($this->data['primaryKey'][$name])) ? 'PRIMARY KEY' : '';
        
        if($this->data['types'][$nameKey]=='timestamp' && !isset($this->data['default'][$nameKey])){
            $this->data['default'][$name] = 'CURRENT_TIMESTAMP';
        }
        
        if($this->data['types'][$nameKey]!=='timestamp'){
            $list[] = (isset($this->data['default'][$name])) ? ' DEFAULT "'.$this->data['default'][$name].'"' : '';
        }
        else{
            $list[] = (isset($this->data['default'][$name])) ? ' DEFAULT '.$this->data['default'][$name].'' : '';
        }
        
        $list[] = (isset($this->data['comment'][$name])) ? ' COMMENT "'.$this->data['comment'][$name].'"' : '';

        return $list;

    }

    /**
     * @param $name
     * @return array
     */
    protected function getUniqueValueList($name)
    {
        //get unique
        if(isset($this->data['unique'][$name])){
            $this->data['uniqueValueList'][]      = 'UNIQUE INDEX '.$this->data['unique'][$name]['value'].' ('.$name.' ASC)';
        }
    }

    /**
     * @param $name
     */
    protected function getIndexValueList($name)
    {
        //get index
        if(isset($this->data['index'][$name])){
            $this->data['indexValueList'][]  = 'INDEX '.$this->data['index'][$name]['value'].' ('.$name.')';
        }
    }

    /**
     * @param $name
     */
    protected function setIndex($name)
    {
        //get unique
        $this->getUniqueValueList($name);

        //get index
        $this->getIndexValueList($name);
    }

    /**
     * @return array
     */
    protected function getCreateDefaultList()
    {
        $list               = [];

        foreach ($this->data['names'] as $key=>$name)
        {
            list(
                $nullableValue,$autoIncrementValue,
                $primaryKeyValue,$defaultValue,$commentValue
                ) = $this->getValueWithIsset($name);

            //set index values
            $this->setIndex($name);

            $list[]=''.$name.' '.$this->data['types'][$key].' '.$nullableValue.' '.$defaultValue.' '.$primaryKeyValue.' '.$autoIncrementValue.' '.$commentValue.'';
        }

        return $list;
    }

    /**
     * @return array
     */
    protected function getKeyList()
    {
        $keyList            = [];

        //multiple indexes
        if(isset($this->data['index']['indexes'])){

            foreach ($this->data['index']['indexes'] as $index_key=>$index_value){

                $indexesCommentValue = (isset($index_value['comment'])) ? 'COMMENT "'.$index_value['comment'].'"' : '';

                $keyType    = (isset($index_value['type'])) ? ucfirst($index_value['type']) : 'KEY';

                $keyList[]  = "".$keyType." ".$index_value['name']." (".implode(",",$index_value['value']).") ".$indexesCommentValue;
            }
        }

        return $keyList;
    }

    /**
     * @param $reference
     * @return string
     */
    protected function getReferenceSyntax($reference)
    {
        $list = [];

        foreach ($reference as $constraint=>$values){

            $list[]=',CONSTRAINT '.$constraint.' FOREIGN KEY ('.$values['key'].') 
            REFERENCES '.$values['references']['table'].'('.$values['references']['field'].') '.$this->getOnProcess($values);
        }

        return implode (" ",$list);
    }

    /**
     * @param $referenceValue
     */
    private function getOnProcess($referenceValue)
    {
        if(isset($referenceValue['on']) && isset($referenceValue['onOption'])){
            return ''.$referenceValue['on'].' '.strtoupper($referenceValue['onOption']).'';
        }

        return '';
    }

}

