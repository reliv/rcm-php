<?php

namespace Rcm\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Rcm\Service\LocaleService;
use Rcm\Service\PhpServer;
use Rcm\Service\SiteService;

/**
 * Class LocaleSetter
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class LocaleSetter
{
    /**
     * @var SiteService
     */
    protected $siteService;

    /**
     * @var LocaleService
     */
    protected $localeService;

    /**
     * LocaleSetter constructor.
     *
     * @param SiteService   $siteService
     * @param LocaleService $localeService
     */
    public function __construct(
        SiteService $siteService,
        LocaleService $localeService
    ) {
        $this->siteService = $siteService;
        $this->localeService = $localeService;
    }

    /**
     * getSiteFromRequest
     *
     * @param RequestInterface $request
     *
     * @return void
     */
    public function getSiteFromRequest(RequestInterface $request)
    {
        $domain = PhpServer::getDomainFormHost(
            $request->getUri()->getHost()
        );

        $this->siteService->getSite(
            $domain
        );
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
        $locale = $this->siteService->getCurrentSite()->getLocale();
        // @todo Set from $request site getSiteFromRequest($request)
        $this->localeService->setLocale($locale);

        return $next($request, $response);
    }
}
