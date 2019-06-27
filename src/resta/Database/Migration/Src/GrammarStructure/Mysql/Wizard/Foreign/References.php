<?php

namespace Migratio\GrammarStructure\Mysql\Wizard\Foreign;

use Migratio\Contract\Foreign\ReferencesContract;

class References implements ReferencesContract
{
    /**
     * @var $wizard WizardContract
     */
    protected $wizard;

    /**
     * @var $constraint
     */
    protected $constraint;

    /**
     * References constructor.
     * @param $wizard
     * @param $constraint
     */
    public function __construct($wizard,$constraint)
    {
        $this->wizard=$wizard;
        $this->constraint=$constraint;
    }

    /**
     * @param $table
     * @param string $field
     * @return \Migratio\Contract\Foreign\OptionsContract
     */
    public function references($table, $field = 'id')
    {
        $this->wizard->setReferences($this->constraint,'references',
            [
                'table'=>$table,
                'field'=>$field
            ]);

        return new Options($this->wizard,$this->constraint);
    }
}