<?php

namespace MBMigration\Layer\HTTP;

use Exception;
use Symfony\Component\HttpFoundation\Request;
class RequestHandlerGET extends RequestHandler
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function getContent($value)
    {
        return $this->request->get($value);
    }

    /**
     * @throws Exception
     */

}
