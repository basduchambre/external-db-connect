<?php
namespace Basduchambre\WifiPortalConnect\Exceptions;

use Exception;

class GenericException extends Exception
{
    public static function create(string $reason): self
    {
        return new static($reason);
    }
}
