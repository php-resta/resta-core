<?php

namespace Migratio\Contract;

interface IndexPropertiesContract {

    /**
     * @return mixed
     */
    public function fulltext();

    /**
     * @return mixed
     */
    public function unique();

}

