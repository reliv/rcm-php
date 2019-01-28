<?php

namespace Rcm\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Api\GetSiteIdByRequest;
use Zend\Diactoros\Response\HtmlResponse;

class SiteExistsCheck
{
    protected $getSiteIdByRequest;
    protected $ignoredUrls;

    /**
     * @param GetSiteIdByRequest $getSiteIdByRequest
     */
    public function __construct(
        GetSiteIdByRequest $getSiteIdByRequest,
        array $ignoredUrls
    ) {
        $this->getSiteIdByRequest = $getSiteIdByRequest;
        $this->ignoredUrls = $ignoredUrls;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     *
     * @return HtmlResponse
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        $urlWithoutParams = $request->getUri()->getPath();

        if (in_array($urlWithoutParams, $this->ignoredUrls)) {
            return $next($request, $response);
        }

        $siteId = $this->getSiteIdByRequest->__invoke(
            $request
        );

        if (empty($siteId)) {
            return new HtmlResponse('Website Not Found', 404);
        }

        return $next($request, $response);
    }
}
