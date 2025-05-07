<?php

namespace MBMigration\Layer\HTTP;

use Symfony\Component\HttpFoundation\Request;

class RequestHandlerDELETE extends RequestHandler
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function getContent($value)
    {
        $content = json_decode($this->request->getContent(), true);

        return $content[$value] ?? null;
    }
}
