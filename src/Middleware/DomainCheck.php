<?php

namespace Rcm\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Rcm\Service\DomainRedirectService;
use Rcm\Service\SiteService;

/**
 * Class DomainCheck
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class DomainCheck
{
    /**
     * @var SiteService
     */
    protected $siteService;

    /**
     * @var DomainRedirectService
     */
    protected $domainRedirectService;

    /**
     * DomainCheck constructor.
     *
     * @param SiteService           $siteService
     * @param DomainRedirectService $domainRedirectService
     */
    public function __construct(
        SiteService $siteService,
        DomainRedirectService $domainRedirectService
    ) {
        $this->siteService = $siteService;
        $this->domainRedirectService = $domainRedirectService;
    }

    /**
     * isConsoleRequest
     *
     * @return bool
     */
    protected function isConsoleRequest()
    {
        return $this->siteService->isConsoleRequest();
    }

    /**
     * __invoke
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param callable|null     $next
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        if ($this->isConsoleRequest()) {
            return $next($request, $response);
        }

        $currentDomain = $this->siteService->getCurrentDomain();

        $site = $this->siteService->getCurrentSite($currentDomain);

        $redirectUrl = $this->domainRedirectService->getSiteNotAvailableRedirectUrl($site);

        if (!$site->isSiteAvailable() && empty($redirectUrl)) {
            return $response->withStatus(404);
        }

        if ($redirectUrl) {
            $response = $response->withHeader('Location', '//' . $redirectUrl);
            return $response->withStatus(302);
        }

        $redirectUrl = $this->domainRedirectService->getPrimaryRedirectUrl($site);

        if ($redirectUrl) {
            $response = $response->withHeader('Location', '//' . $redirectUrl);
            return $response->withStatus(302);
        }

        return $next($request, $response);
    }
}
