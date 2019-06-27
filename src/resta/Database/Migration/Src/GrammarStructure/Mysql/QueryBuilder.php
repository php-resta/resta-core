<?php

namespace Migratio\GrammarStructure\Mysql;

use Migratio\GrammarStructure\Mysql\Traits\QueryStack;

class QueryBuilder extends QuerySyntax
{
    use QueryStack;

    /**
     * @var $schema
     */
    protected $schema;

    /**
     * @var $object
     */
    protected $object;

    /**
     * @var $table
     */
    protected $table;

    /**
     * QueryBuilder constructor.
     * @param $schema
     * @param $object
     */
    public function __construct($schema,$table,$object)
    {
        $this->schema   = $schema;
        $this->table    = $table;
        $this->object   = $object;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        $schemaType = $this->object->getSchemaType();

        return $this->{$schemaType}();
    }

    /**
     * @return mixed
     */
    public function create()
    {
        return $this->syntaxCreate();
    }

    /**
     * @return mixed
     */
    public function alter()
    {
        return $this->syntaxAlter();
    }


}

