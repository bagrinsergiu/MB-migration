<?php

namespace MBMigration\Builder\Layout\Common\Exception;

class BrizyKitNotFound extends \Exception
{
    public function __construct(string $message = "Brizy kit element not found", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}