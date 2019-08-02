<?php

namespace Rcm\CurrentRequest;

use Psr\Http\Message\ServerRequestInterface;

class GetCurrentRequest
{
    protected $currentRequest;

    public function __invoke(): ServerRequestInterface
    {
        if ($this->currentRequest === null) {
            throw new \Exception(
                'GetCurrentRequest->__invoke was called before GetCurrentRequest->setCurrentRequest'
                . ' was called. This is probably because you did not register the current request middleware early'
                . ' in the pipe.'
            );
        }

        return $this->currentRequest;
    }

    public function setCurrentRequest(ServerRequestInterface $request)
    {
        $this->currentRequest = $request;
    }
}
