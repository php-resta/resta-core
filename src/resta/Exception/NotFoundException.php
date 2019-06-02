<?php

namespace Resta\Exception;

use Exception;
use Throwable;

class NotFoundException extends Exception
{
    /**
     * @var string
     */
    protected $lang = 'notFoundException';

    /**
     * NotFoundException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "The requested endpoint was not found.", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}