<?php

namespace Migratio\GrammarStructure\Mysql\Wizard;

use Migratio\Contract\NameContract;
use Migratio\Contract\WizardAlterContract;
use Migratio\Contract\WizardAlterGroupContract;

class WizardAlterGroup extends Wizard implements WizardAlterGroupContract
{
    /**
     * @var array 
     */
    protected $alterBinds = array();
    
    /**
     * add column
     * 
     * @return WizardAlterContract
     */
    public function addColumn()
    {
        $this->alterBinds[] = __FUNCTION__;
        return $this->getWizardAlterInstance(__FUNCTION__);
    }

    /**
     * drop column
     * 
     * @return NameContract
     */
    public function dropColumn()
    {
        $this->alterBinds[] = __FUNCTION__;
        return $this->dropWizardAlterInstance(__FUNCTION__);
    }

    /**
     * change column
     * 
     * @return WizardAlterContract
     */
    public function change()
    {
        $this->alterBinds[] = __FUNCTION__;
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

