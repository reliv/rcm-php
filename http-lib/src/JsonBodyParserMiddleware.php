<?php

namespace Rcm\HttpLib;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\RcmApiLib\Api\ApiResponse\NewPsrResponseWithTranslatedMessages;
use Reliv\RcmApiLib\Model\HttpStatusCodeApiMessage;

class JsonBodyParserMiddleware implements MiddlewareInterface
{
    /**
     * @var NewPsrResponseWithTranslatedMessages
     */
    protected $newPsrResponseWithTranslatedMessages;

    /**
     * @param NewPsrResponseWithTranslatedMessages $newPsrResponseWithTranslatedMessages
     */
    public function __construct(
        NewPsrResponseWithTranslatedMessages $newPsrResponseWithTranslatedMessages
    ) {
        $this->newPsrResponseWithTranslatedMessages = $newPsrResponseWithTranslatedMessages;
    }

    /**
     * match
     *
     * @param $contentType
     *
     * @return bool
     */
    public function match($contentType)
    {
        $parts = explode(';', $contentType);
        $mime = array_shift($parts);

        return (bool)preg_match('#[/+]json$#', trim($mime));
    }

    /**
     * Adds JSON decoded request body to the request, where appropriate.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface|void
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $contentType = $request->getHeaderLine('Content-Type');

        if (!$this->match($contentType)) {
            return $delegate->process($request);
        }

        $rawBody = (string)$request->getBody();
        $parsedBody = json_decode($rawBody, true);

        if (!empty($rawBody) && json_last_error() !== JSON_ERROR_NONE) {
            $statusCode = 400;
            $apiMessage = new HttpStatusCodeApiMessage($statusCode);
            $apiMessage->setCode('invalid-json');
            $apiMessage->setValue('Invalid JSON');
            $apiResponse = $this->newPsrResponseWithTranslatedMessages->__invoke(
                null,
                $statusCode,
                $apiMessage
            );

            return $apiResponse;
        }

        $request = $request
            ->withAttribute('rawBody', $rawBody)
            ->withParsedBody($parsedBody);

        return $delegate->process($request);
    }
}
