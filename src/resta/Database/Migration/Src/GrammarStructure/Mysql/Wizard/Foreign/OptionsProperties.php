<?php

namespace Migratio\GrammarStructure\Mysql\Wizard\Foreign;

use Migratio\Contract\Foreign\OptionsPropertiesContract;

class OptionsProperties implements OptionsPropertiesContract
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
     * OptionsProperties constructor.
     * @param $wizard
     * @param $constraint
     */
    public function __construct($wizard,$constraint)
    {
        $this->wizard=$wizard;
        $this->constraint=$constraint;
    }

    /**
     * @return mixed
     */
    public function cascade()
    {
        $this->wizard->setReferences($this->constraint,'onOption',__FUNCTION__);
    }

    /**
     * @return mixed
     */
    public function noAction()
    {
        $this->wizard->setReferences($this->constraint,'onOption',__FUNCTION__);
    }

    /**
     * @return mixed
     */
    public function restrict()
    {
        $this->wizard->setReferences($this->constraint,'onOption',__FUNCTION__);
    }

    /**
     * @return mixed
     */
    public function setDefault()
    {
        $this->wizard->setReferences($this->constraint,'onOption',__FUNCTION__);
    }

    /**
     * @return mixed
     */
    public function setNull()
    {
        $this->wizard->setReferences($this->constraint,'onOption',__FUNCTION__);
    }


}