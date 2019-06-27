<?php

namespace Migratio\Contract\Foreign;

use Migratio\Contract\WizardAlterContract;

interface OptionsPropertiesContract
{
    /**
     * @return mixed
     */
    public function cascade();

    /**
     * @return mixed
     */
    public function noAction();

    /**
     * @return mixed
     */
    public function restrict();

    /**
     * @return mixed
     */
    public function setDefault();

    /**
     * @return mixed
     */
    public function setNull();
}

