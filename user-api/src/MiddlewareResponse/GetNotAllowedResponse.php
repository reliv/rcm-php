<?php

namespace RcmUser\Api\MiddlewareResponse;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Result;
use Zend\Diactoros\Response\JsonResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetNotAllowedResponse
{
    const DEFAULT_NOT_ALLOWED_STATUS = 401;

    /**
     * @param ServerRequestInterface $request
     * @param string                 $reasonPhrase
     * @param int                    $notAllowedStatus
     *
     * @return JsonResponse
     */
    public function __invoke(
        ServerRequestInterface $request,
        string $reasonPhrase = '',
        int $notAllowedStatus = self::DEFAULT_NOT_ALLOWED_STATUS
    ) {
        $serverPrams = $request->getServerParams();
        $version = $serverPrams['SERVER_PROTOCOL'] ?? '1.1';
        if (empty($reasonPhrase)) {
            $reasonPhrase = 'Not Allowed';
        }
        $message = sprintf(
            '%s %d %s',
            $version,
            $notAllowedStatus,
            $reasonPhrase
        );

        $result = new Result(
            null,
            Result::CODE_FAIL,
            $message
        );

        return new JsonResponse(
            $result,
            $notAllowedStatus,
            [
                'reason-phrase' => $reasonPhrase
            ]
        );
    }
}
