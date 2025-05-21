<?php

namespace MBMigration\Bridge;

use MBMigration\Bridge\Interfaces\ResponseHandlerInterface;

/**
 * Handles HTTP responses
 */
class ResponseHandler implements ResponseHandlerInterface
{
    private MgResponse $mgResponse;

    /**
     * Initialize the response handler
     *
     * @param MgResponse $mgResponse The response object
     */
    public function __construct(MgResponse $mgResponse = null)
    {
        $this->mgResponse = $mgResponse ?? new MgResponse();
    }

    /**
     * Get the response object
     *
     * @return MgResponse The response object
     */
    public function getResponse(): MgResponse
    {
        return $this->mgResponse;
    }

    /**
     * Prepare a response message
     *
     * @param mixed $body The response body
     * @param string $type The response type (value, error, message)
     * @param int $code The HTTP status code
     * @return ResponseHandlerInterface
     */
    public function prepareMessage($body, string $type = 'value', int $code = 200): ResponseHandlerInterface
    {
        $this->mgResponse
            ->setMessage($body, $type)
            ->setStatusCode($code);

        return $this;
    }

    /**
     * Prepare a success response
     *
     * @param mixed $data The response data
     * @param int $code The HTTP status code
     * @return ResponseHandlerInterface
     */
    public function success($data, int $code = 200): ResponseHandlerInterface
    {
        return $this->prepareMessage($data, 'value', $code);
    }

    /**
     * Prepare an error response
     *
     * @param string $message The error message
     * @param int $code The HTTP status code
     * @return ResponseHandlerInterface
     */
    public function error(string $message, int $code = 400): ResponseHandlerInterface
    {
        return $this->prepareMessage($message, 'error', $code);
    }

    /**
     * Prepare a not found response
     *
     * @param string $message The error message
     * @return ResponseHandlerInterface
     */
    public function notFound(string $message = 'Resource not found'): ResponseHandlerInterface
    {
        return $this->error($message, 404);
    }

    /**
     * Prepare a bad request response
     *
     * @param string $message The error message
     * @return ResponseHandlerInterface
     */
    public function badRequest(string $message = 'Bad request'): ResponseHandlerInterface
    {
        return $this->error($message, 400);
    }

    /**
     * Prepare an unauthorized response
     *
     * @param string $message The error message
     * @return ResponseHandlerInterface
     */
    public function unauthorized(string $message = 'Unauthorized'): ResponseHandlerInterface
    {
        return $this->error($message, 401);
    }
}
