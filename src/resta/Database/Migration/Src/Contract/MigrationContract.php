<?php

namespace Migratio\Contract;

use Migratio\Contract\SchemaCapsuleContract as Schema;

interface MigrationContract {

    /**
     * @param SchemaCapsuleContract $schema
     * @return mixed
     */
    public function up(Schema $schema);

    /**
     * @return mixed
     */
    public function down();

}

