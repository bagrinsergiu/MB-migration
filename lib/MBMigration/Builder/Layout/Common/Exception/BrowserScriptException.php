<?php

namespace MBMigration\Builder\Layout\Common\Exception;

class BrowserScriptException extends \Exception
{
    public function __construct(string $message = "Page Section not found", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}