<?php

namespace RcmUser\Api\MiddlewareResponse;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Result;
use Zend\Diactoros\Response\JsonResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetExceptionResponse
{
    const DEFAULT_EXCEPTION_STATUS = 500;

    /**
     * @param ServerRequestInterface $request
     * @param \Throwable             $exception
     * @param string                 $reasonPhrase
     * @param int                    $exceptionStatus
     *
     * @return JsonResponse
     */
    public function __invoke(
        ServerRequestInterface $request,
        \Throwable $exception,
        string $reasonPhrase = '',
        int $exceptionStatus = self::DEFAULT_EXCEPTION_STATUS
    ) {
        $result = new Result(
            null,
            Result::CODE_FAIL,
            "Code: " . $exception->getCode() . ": " . $exception->getMessage()
        );

        if (empty($reasonPhrase)) {
            $reasonPhrase = 'Internal Server Error: ' . $exception->getMessage();
        }

        /*
        . " | " . $exception->getFile() .
         ":" . $exception->getLine() .
         " | " . $exception->getTraceAsString()
        */

        return new JsonResponse(
            $result,
            self::DEFAULT_EXCEPTION_STATUS,
            [
                'reason-phrase' => $reasonPhrase
            ]
        );
    }
}
