<?php

namespace Library\Exceptions;

use Throwable;

class JobFailedException extends AbstractException
{
    public function __construct($message = "", $code = 510, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}