<?php

namespace Migratio\GrammarStructure\Mysql\Wizard;

use Migratio\Contract\IndexPropertiesContract;

class IndexProperties implements IndexPropertiesContract
{
    /**
     * @var $wizard WizardContract
     */
    protected $wizard;

    /**
     * @var $index_name
     */
    protected $index_name;

    /**
     * IndexProperties constructor.
     * @param $index_name
     * @param $wizard
     */
    public function __construct($index_name,$wizard)
    {
        $this->wizard       = $wizard;
        $this->index_name   = $index_name;
    }

    /**
     * @return mixed|void
     */
    public function fulltext()
    {
        $this->wizard->updateIndexesForSpecialist($this->index_name,__FUNCTION__);
    }

    /**
     * @return mixed|void
     */
    public function unique()
    {
        $this->wizard->updateIndexesForSpecialist($this->index_name,__FUNCTION__);
    }

}

