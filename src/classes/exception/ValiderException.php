<?php

namespace sae\web\exception;

class ValiderException extends \Exception
{
    public function __construct($message = "", $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}