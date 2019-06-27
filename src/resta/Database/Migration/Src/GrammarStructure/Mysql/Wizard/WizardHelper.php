<?php

namespace Migratio\GrammarStructure\Mysql\Wizard;

class WizardHelper
{
    /**
     * @return mixed
     */
    public function getAutoIncrement()
    {
        return $this->auto_increment;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return mixed
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return mixed
     */
    public function getNames()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @return mixed
     */
    public function getReferences()
    {
        return $this->references;
    }

    /**
     * @return mixed
     */
    public function getSchemaType()
    {
        return $this->schemaType;
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return mixed
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @return mixed
     */
    public function getUnique()
    {
        return $this->unique;
    }

    /**
     * @param $message
     */
    public function setError($message)
    {
        $this->error[]=$message;
    }

    /**
     * @param $engine
     */
    public function setEngine($engine)
    {
        $this->engine=$engine;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name[]=$name;
    }

    /**
     * @param $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setAlterType($key,$value)
    {
        $this->alterType[$key]=$value;
    }

    /**
     * @param $table
     */
    public function setTable($table)
    {
        $this->table=$table;
    }

    /**
     * @param $schemaType
     */
    public function schemaType($schemaType)
    {
        $this->schemaType=$schemaType;
    }

    /**
     * @param $type
     * @param null $value
     * @param null $cond
     */
    public function setTypes($type,$value=null,$cond=null)
    {
        if(!is_array($value) && $value!==null){
            $this->types[]=''.$type.'('.$value.')';
        }
        else{

            if($cond=='enum'){
                $this->types[]="".$type."('".implode("','",$value)."')";
            }
            else{
                $this->types[]=''.$type.'';
            }

        }

    }

    /**
     * @return mixed
     */
    protected function getLastName(){

        return end($this->name);
    }

    /**
     * @param $collation
     */
    public function getCollation()
    {
        return $this->collation;
    }

    /**
     * @return mixed
     */
    public function getNullable()
    {
        return $this->nullable;
    }

    /**
     * @param $name
     * @param $data
     * @param string $specialist
     */
    public function updateIndexesForSpecialist($name,$data,$specialist='type')
    {
        $indexes = $this->getIndex();

        foreach ($indexes['indexes'] as $key=>$index){

            if($index['name']==$name){
                $this->index['indexes'][$key][$specialist]=$data;
            }
        }
    }

    /**
     * @param $constraint
     * @param $key
     * @param $value
     */
    public function setReferences($constraint,$key,$value)
    {
        $this->references[$constraint][$key]=$value;
    }

    /**
     * @return mixed
     */
    public function getAlterType()
    {
        return $this->alterType;
    }
}

