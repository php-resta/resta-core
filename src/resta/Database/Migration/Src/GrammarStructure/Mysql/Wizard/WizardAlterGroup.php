<?php

namespace Migratio\GrammarStructure\Mysql\Wizard;

use Migratio\Contract\NameContract;
use Migratio\Contract\WizardAlterContract;
use Migratio\Contract\WizardAlterGroupContract;

class WizardAlterGroup extends Wizard implements WizardAlterGroupContract
{
    /**
     * add column
     * 
     * @return WizardAlterContract
     */
    public function addColumn()
    {
        return $this->getWizardAlterInstance(__FUNCTION__);
    }

    /**
     * drop column
     * 
     * @return NameContract
     */
    public function dropColumn()
    {
        return $this->dropWizardAlterInstance(__FUNCTION__);
    }

    /**
     * change column
     * 
     * @return WizardAlterContract
     */
    public function change()
    {
        return $this->getWizardAlterInstance(__FUNCTION__);
    }

    /**
     * get wizard alter instance
     * 
     * @param $group
     * @return WizardAlter
     */
    private function getWizardAlterInstance($group)
    {
        $this->setAlterType('group',$group);

        return new WizardAlter($this);
    }

    /**
     * @param $group
     * @return $this
     */
    private function dropWizardAlterInstance($group)
    {
        $this->setAlterType('group',$group);

        return $this;
    }
}

