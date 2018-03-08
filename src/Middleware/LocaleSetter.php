<?php

namespace Rcm\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
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
    const ATTRIBUTE_SITE_LOCALE = 'rcm-site-locale';

    /**
     * @var SiteService
     */
    protected $siteService;

    /**
     * @var LocaleService
     */
    protected $localeService;

    /**
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
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $locale = $this->siteService->getCurrentSite()->getLocale();
        // @todo Set from $request site getSiteFromRequest($request)
        $this->localeService->setLocale($locale);

        return $next(
            $request->withAttribute(self::ATTRIBUTE_SITE_LOCALE, $locale),
            $response
        );
    }
}
