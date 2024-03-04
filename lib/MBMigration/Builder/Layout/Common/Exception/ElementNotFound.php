<?php

namespace MBMigration\Builder\Layout\Common\Exception;

use Exception;
class ElementNotFound extends Exception
{
    public function __construct(string $message = "Theme Element not found", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}