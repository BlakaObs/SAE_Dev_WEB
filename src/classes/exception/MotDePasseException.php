<?php

namespace sae\web\exception;

use Exception;
use Throwable;

class MotDePasseException extends Exception
{
    public function __construct($message = "", $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}