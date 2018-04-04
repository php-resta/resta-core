<?php

namespace Resta;

use Resta\Contracts\StaticNamespaceContracts;

class StaticNamespaceRepository extends StaticPathRepository implements StaticNamespaceContracts {

    /**
     * @return mixed
     */
    public function optionalException(){
        return Utils::getNamespace($this->appOptionalException());
    }

}