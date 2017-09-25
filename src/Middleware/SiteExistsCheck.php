<?php

namespace Rcm\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Api\GetSiteIdByRequest;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class SiteExistsCheck
{
    /**
     * @param GetSiteIdByRequest $getSiteIdByRequest
     */
    public function __construct(
        GetSiteIdByRequest $getSiteIdByRequest
    ) {
        $this->getSiteIdByRequest = $getSiteIdByRequest;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return HtmlResponse
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        $siteId = $this->getSiteIdByRequest->__invoke(
            $request
        );

        if (empty($siteId)) {
            return new HtmlResponse('', 404);
        }

        return $next($request, $response);
    }
}
