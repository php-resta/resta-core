<?php

namespace Migratio\Contract;

use Migratio\GrammarStructure\Mysql\Wizard\Types;

interface WizardContract {

    /**
     * @return mixed
     */
    public function auto_increment();

    /**
     * @param $value
     * @param bool $table
     * @return TablePropertiesContract
     */
    public function collation($value,$table=false);

    /**
     * @param $value
     * @return mixed
     */
    public function comment($value);

    /**
     * @param $value
     * @return $this
     */
    public function default($value);

    /**
     * @param null $name
     * @param null $value
     * @param bool $key
     * @return $this
     */
    public function index($name=null,$value=null,$key=false);

    /**
     * @param $value
     * @return Types
     */
    public function name($value);

    /**
     * @param bool $null
     * @return $this
     */
    public function nullable($null=true);

    /**
     * @return $this
     */
    public function primaryKey();

    /**
     * @return TablePropertiesContract
     */
    public function table();

    /**
     * @param null $name
     * @param null $value
     * @return mixed
     */
    public function unique($name=null,$value=null);

}

