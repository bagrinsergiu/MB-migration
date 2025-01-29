<?php

namespace MBMigration\Builder\Layout\Common\Exception;

use Exception;
class BadJsonProvided extends Exception
{
    public function __construct(string $message = "Bad json provided", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}