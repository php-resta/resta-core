<?php

namespace Migratio\GrammarStructure\Mysql\Wizard;

use Migratio\Contract\WizardContract;
use Migratio\Contract\WizardAlterContract;

class WizardAlter extends Wizard implements WizardContract,WizardAlterContract
{
    /**
     * @var string
     */
    protected $after;

    /**
     * WizardAlter constructor.
     * @param $wizard
     */
    public function __construct($wizard)
    {
        $this->wizard = $wizard;
    }

    /**
     * @param $field
     * @return $this
     */
    public function after($field)
    {
        $this->wizard->setAlterType('place',['AFTER'=>$field]);

        return $this->wizard;
    }
}

