<?php
namespace Basduchambre\JuniperMist\Exceptions;

use Exception;

class InvalidApiToken extends Exception
{
    public static function create(string $reason): self
    {
        return new static($reason);
    }
}
