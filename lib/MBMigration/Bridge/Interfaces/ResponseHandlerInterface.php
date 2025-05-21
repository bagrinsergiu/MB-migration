<?php

namespace MBMigration\Bridge\Interfaces;

use MBMigration\Bridge\MgResponse;

/**
 * Interface for response handler
 */
interface ResponseHandlerInterface
{
    /**
     * Get the response object
     *
     * @return MgResponse The response object
     */
    public function getResponse(): MgResponse;

    /**
     * Prepare a response message
     *
     * @param mixed $body The response body
     * @param string $type The response type (value, error, message)
     * @param int $code The HTTP status code
     * @return ResponseHandlerInterface
     */
    public function prepareMessage($body, string $type = 'value', int $code = 200): ResponseHandlerInterface;

    /**
     * Prepare a success response
     *
     * @param mixed $data The response data
     * @param int $code The HTTP status code
     * @return ResponseHandlerInterface
     */
    public function success($data, int $code = 200): ResponseHandlerInterface;

    /**
     * Prepare an error response
     *
     * @param string $message The error message
     * @param int $code The HTTP status code
     * @return ResponseHandlerInterface
     */
    public function error(string $message, int $code = 400): ResponseHandlerInterface;

    /**
     * Prepare a not found response
     *
     * @param string $message The error message
     * @return ResponseHandlerInterface
     */
    public function notFound(string $message = 'Resource not found'): ResponseHandlerInterface;

    /**
     * Prepare a bad request response
     *
     * @param string $message The error message
     * @return ResponseHandlerInterface
     */
    public function badRequest(string $message = 'Bad request'): ResponseHandlerInterface;

    /**
     * Prepare an unauthorized response
     *
     * @param string $message The error message
     * @return ResponseHandlerInterface
     */
    public function unauthorized(string $message = 'Unauthorized'): ResponseHandlerInterface;
}
