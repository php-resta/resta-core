<?php

namespace Migratio\GrammarStructure\Mysql\Wizard;

use Migratio\Contract\TablePropertiesContract;
use Migratio\Contract\WizardContract;

class Wizard extends WizardHelper implements WizardContract
{
    /**
     * @var $randomInstance
     */
    public static $randomInstance;

    /**
     * @var array $error
     */
    protected $error = array();

    /**
     * @var array
     */
    protected $alterType = [];

    /**
     * @var array
     */
    protected $auto_increment=array();

    /**
     * @var $collation
     */
    protected $collation;

    /**
     * @var $comment
     */
    protected $comment;

    /**
     * @var array $primaryKey
     */
    protected $primaryKey=array();

    /**
     * @var array $references
     */
    protected $references=array();

    /**
     * @var $schemaType
     */
    protected $schemaType;

    /**
     * @var $file
     */
    protected $file;

    /**
     * @var $name array
     */
    protected $name=array();

    /**
     * @var array $nullable
     */
    protected $nullable=array();

    /**
     * @var array $types
     */
    protected $types=array();

    /**
     * @var $engine
     */
    protected $engine;

    /**
     * @var $default array
     */
    protected $default=array();

    /**
     * @var array $index
     */
    protected $index=array();

    /**
     * @var array $unique
     */
    protected $unique=array();

    /**
     * @var $table
     */
    protected $table;

    /**
     * @return mixed|void
     */
    public function auto_increment()
    {
        if(count($this->auto_increment)==0){

            if($this->getLastName()===false){
                $this->name[]='id';
                $this->setTypes('int',14);
            }
            $this->auto_increment[$this->getLastName()]=true;
            $this->primaryKey();
        }
    }

    /**
     * @param $value
     * @return $this
     */
    public function comment($value)
    {
        $this->comment[$this->getLastName()]=$value;

        return $this;
    }

    /**
     * @param $name
     * @return Types
     */
    public function name($name)
    {
        if(in_array($name,$this->name)){
           $this->setError('You have written the '.$name.' name more than 1.');
        }

        $this->name[]=$name;

        return new Types($this);

    }

    /**
     * @param $value
     * @return $this|mixed
     */
    public function default($value)
    {
        $this->default[$this->getLastName()]=$value;

        return $this;
    }

    /**
     * @param $value
     * @param bool $table
     * @return $this
     */
    public function collation($value,$table=false)
    {
        if($table===false)
        {
            $this->collation[$this->getLastName()]=$value;
        }
        else{
            $this->collation['table']=$value;
        }

        return $this;
    }

    /**
     * @param null $name
     * @param null $value
     * @param bool $key
     * @return $this|WizardContract
     */
    public function index($name=null,$value=null,$key=false)
    {
        $name   = ($name===null) ? $this->getLastName() : $name;
        $value  = ($value===null) ? $name : $value;

        if($key===false){
            $this->index[$this->getLastName()]=['name'=>$name,'value'=>$value];
        }

        if($key===true){
            $this->index['indexes'][]=['name'=>$name,'value'=>$value];
        }

        return $this;
    }

    /**
     * @param bool $null
     * @return $this
     */
    public function nullable($null=true)
    {
        $this->nullable[$this->getLastName()]=$null;

        return $this;
    }

    /**
     * @return $this|WizardContract
     */
    public function primaryKey()
    {
        $this->primaryKey[$this->getLastName()]=true;

        return $this;
    }

    /**
     * @return TablePropertiesContract
     */
    public function table()
    {
        return new TableProperties($this);
    }

    /**
     * @param $name
     * @param null $value
     * @return $this|mixed
     */
    public function unique($name=null,$value=null)
    {
        $name   = ($name===null) ? $this->getLastName() : $name;
        $value  = ($value===null) ? $name : $value;

        $this->unique[$this->getLastName()]=['name'=>$name,'value'=>$value];

        return $this;
    }
}

