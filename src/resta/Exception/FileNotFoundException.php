<?php

namespace Resta\Exception;

use Exception;
use Throwable;

class FileNotFoundException extends Exception
{
    /**
     * @var string
     */
    protected $lang = 'fileNotFoundException';

    /**
     * FileNotFoundException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "File does not exist at the specified path", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}