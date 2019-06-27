<?php

namespace Migratio\Contract;

use Migratio\Contract\Foreign\ForeignContract;
use Migratio\GrammarStructure\Mysql\Wizard\IndexProperties;
use Migratio\GrammarStructure\Mysql\Wizard\EngineProperties;

interface TablePropertiesContract {

    /**
     * @param string $value
     * @return mixed
     */
    public function collation($value='utf8');

    /**
     * @return EngineProperties
     */
    public function engine();

    /**
     * @return ForeignContract
     */
    public function foreign();

    /**
     * @param $index_name
     * @param array $fields
     * @param null $comment
     * @return IndexPropertiesContract
     */
    public function indexes($index_name,$fields=array(),$comment=null);

}

