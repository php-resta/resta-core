<?php

namespace Migratio\GrammarStructure\Mysql\Wizard\Foreign;

use Migratio\Contract\Foreign\OptionsContract;

class Options implements OptionsContract
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
     * Options constructor.
     * @param $wizard
     * @param $constraint
     */
    public function __construct($wizard,$constraint)
    {
        $this->wizard=$wizard;
        $this->constraint=$constraint;
    }

    /**
     * @return \Migratio\Contract\Foreign\OptionsPropertiesContract
     */
    public function onDelete()
    {
        $this->wizard->setReferences($this->constraint,'on','ON DELETE');
        return new OptionsProperties($this->wizard,$this->constraint);
    }

    /**
     * @return \Migratio\Contract\Foreign\OptionsPropertiesContract
     */
    public function onUpdate()
    {
        $this->wizard->setReferences($this->constraint,'on','ON UPDATE');
        return new OptionsProperties($this->wizard,$this->constraint);
    }

}