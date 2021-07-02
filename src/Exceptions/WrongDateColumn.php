<?php

namespace Basduchambre\ExternalDbConnect\Exceptions;

use Exception;

class WrongDateColumn extends Exception
{
    public static function create(string $reason): self
    {
        return new static($reason);
    }
}
