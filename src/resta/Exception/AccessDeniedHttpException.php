<?php

namespace Resta\Exception;

use Exception;
use Throwable;

class AccessDeniedHttpException extends Exception
{
    /**
     * @var string
     */
    protected $lang = 'accessDeniedHttpException';

    /**
     * FileNotFoundException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Your access has been denied.You have limited authority for this section.", $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}