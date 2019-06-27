<?php

namespace Migratio\Contract\Foreign;

use Migratio\Contract\WizardAlterContract;

interface OptionsContract
{
    /**
     * @return OptionsPropertiesContract;
     */
    public function onDelete();

    /**
     * @return OptionsPropertiesContract
     */
    public function onUpdate();
}

