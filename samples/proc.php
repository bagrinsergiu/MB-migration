<?php
$dirToLog = __DIR__ . '/proc.log';

$result = $argv[1] ?? "Параметры не найдены";

file_put_contents($dirToLog, $result . "\n", FILE_APPEND);