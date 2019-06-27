<?php

namespace Migratio\GrammarStructure\Mysql\Wizard;

use Migratio\Contract\WizardContract;
use Migratio\Contract\IndexPropertiesContract;
use Migratio\Contract\TablePropertiesContract;
use Migratio\GrammarStructure\Mysql\Wizard\Foreign\Foreign;

class TableProperties implements TablePropertiesContract
{
    /**
     * @var $wizard WizardContract
     */
    protected $wizard;

    /**
     * Types constructor.
     * @param $wizard WizardContract
     * @param $wizard
     */
    public function __construct($wizard)
    {
        $this->wizard=$wizard;
    }

    /**
     * @param string $value
     * @return mixed|void
     */
    public function collation($value = 'utf8')
    {
        $this->wizard->collation($value,'true');
    }

    /**
     * @return EngineProperties|mixed
     */
    public function engine()
    {
        return new EngineProperties($this->wizard);
    }

    /**
     * @return \Migratio\Contract\Foreign\ForeignContract|Foreign
     */
    public function foreign()
    {
        return new Foreign($this->wizard);
    }

    /**
     * @param $index_name
     * @param array $fields
     * @param null $comment
     * @return IndexPropertiesContract
     */
    public function indexes($index_name, $fields = array(),$comment=null)
    {
        $this->wizard->index($index_name,$fields,true);

        if($comment!==null) $this->wizard->updateIndexesForSpecialist($index_name,$comment,'comment');

        return new IndexProperties($index_name,$this->wizard);
    }

}

