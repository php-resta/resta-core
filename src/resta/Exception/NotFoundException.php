<?php

namespace Resta\Exception;

use Exception;

class NotFoundException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'The requested endpoint was not found.';

    /**
     * @var string
     */
    protected $lang = 'notFoundException';

    /**
     * @var string
     */
    protected $code = 404;
}