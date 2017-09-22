<?php

namespace Rcm\Api;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetSiteIdByRequest
{
    public function __construct(
        GetSiteByRequest $getSiteByRequest
    ) {
        $this->getSiteByRequest = $getSiteByRequest;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return int|string|null
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ) {
        $site = $this->getSiteByRequest->__invoke(
            $request,
            $options
        );

        if (empty($site)) {
            return null;
        }

        return $site->getSiteId();
    }
}
