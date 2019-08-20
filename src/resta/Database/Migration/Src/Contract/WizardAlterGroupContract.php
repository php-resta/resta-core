<?php

namespace Migratio\Contract;

use Migratio\GrammarStructure\Mysql\Wizard\WizardAlter;

interface WizardAlterGroupContract {

    /**
     * @return WizardAlterContract
     */
    public function addColumn();

    /**
     * @return IndexContract
     */
    public function addIndex();

    /**
     * @return UniqueContract
     */
    public function addUnique();
    
    /**
     * @return WizardAlterContract
     */
    public function change();

    /**
     * @return NameContract
     */
    public function dropColumn();

    /**
     * @return DropKeyContract
     */
    public function dropKey();

}

