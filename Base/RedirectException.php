<?php
namespace Base;

use Throwable;

class RedirectException extends Exception
{
    private $_location;

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->_location = $message;
    }

    public function getLocation()
    {
        return $this->_location;
    }
}