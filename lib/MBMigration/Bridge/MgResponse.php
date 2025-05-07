<?php

namespace MBMigration\Bridge;

class MgResponse
{
    private array $message = ['code' => 200, 'body' => ''];

    public function getMessage(): array
    {
        return $this->message['body'];
    }

    public function getStatusCode(): int
    {
        return $this->message['code'];
    }

    public function setMessage($message, $type = 'value'): MgResponse
    {
        $this->message['body'] = [$type => $message];

        return $this;
    }

    public function setStatusCode($code): MgResponse
    {
        $this->message['code'] = $code;

        return $this;
    }
}
