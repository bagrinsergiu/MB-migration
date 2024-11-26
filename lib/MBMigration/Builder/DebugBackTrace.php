<?php

namespace MBMigration\Builder;

trait DebugBackTrace
{
    protected static function trace($level = 1): string
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, $level + 1);
        $caller = $backtrace[$level];
        $line = '(' . basename($caller['file']) . ':' . $caller['line'] . ') ';
        return "Details: $line";
    }
}