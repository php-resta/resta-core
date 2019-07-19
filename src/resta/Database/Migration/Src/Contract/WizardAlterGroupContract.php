<?php

namespace Migratio\Contract;

use Migratio\GrammarStructure\Mysql\Wizard\WizardAlter;

interface WizardAlterGroupContract {

    /**
     * @return WizardAlterContract
     */
    public function addColumn();

    /**
     * @return WizardAlterContract
     */
    public function change();

    /**
     * @return NameContract
     */
    public function dropColumn();

}

