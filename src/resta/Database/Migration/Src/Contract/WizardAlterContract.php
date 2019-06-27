<?php

namespace Migratio\Contract;

interface WizardAlterContract {

    /**
     * @param $field
     * @return WizardContract
     */
    public function after($field);

}

