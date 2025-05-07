<?php

namespace MBMigration\Layer\HTTP;

use Exception;

abstract class RequestHandler
{
    /**
     * @throws Exception
     */
    public function checkInputProperties(array $properties): array
    {
        $checkedProperties = [];

        foreach ($properties as $key) {
            $result = $this->getContent($key);
            $this->checkInputResult($result, $key);
            $checkedProperties[$key] = $result;
        }

        return $checkedProperties;
    }

    /**
     * @throws Exception
     */
    private function checkInputResult($value, $key): void
    {
        if (empty($value)) {
            throw new Exception("Value for '{$key}' is empty or not set.", 400);
        }
    }

    abstract protected function getContent($value);
}
