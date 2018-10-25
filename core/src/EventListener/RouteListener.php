<?php

namespace Rcm\EventListener;

use Rcm\Service\DomainRedirectService;
use Rcm\Service\LocaleService;
use Rcm\Service\RedirectService;
use Rcm\Service\SiteService;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;

/**
 * RCM Route Listener
 *
 * This Route listener check that the domain requested is known to the CMS.
 * It will also test the request url to see if a defined redirect exists and
 * redirect the requester if needed.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class RouteListener
{
    /**
     * @var SiteService
     */
    protected $siteService;

    /**
     * @var RedirectService
     */
    protected $redirectService;

    /**
     * @var LocaleService
     */
    protected $localeService;

    /**
     * RouteListener constructor.
     *
     * @param SiteService $siteService
     * @param RedirectService $redirectService
     * @param DomainRedirectService $domainRedirectService
     * @param LocaleService $localeService
     */
    public function __construct(
        SiteService $siteService,
        RedirectService $redirectService,
        DomainRedirectService $domainRedirectService,
        LocaleService $localeService
    ) {
        $this->siteService = $siteService;
        $this->redirectService = $redirectService;
        $this->domainRedirectService = $domainRedirectService;
        $this->localeService = $localeService;
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
     * Check the domain is a known domain for the CMS.  If not the primary, it will
     * redirect the user to the primary domain.  Useful for multiple domain sites.
     *
     * @param MvcEvent $event Zend MVC Event
     *
     * @return null|Response
     */
    public function checkDomain(MvcEvent $event)
    {
        if ($this->isConsoleRequest()) {
            return null;
        }

        $currentDomain = $this->siteService->getCurrentDomain();

        $site = $this->siteService->getCurrentSite($currentDomain);

        $redirectUrl = $this->domainRedirectService->getSiteNotAvailableRedirectUrl($site);

        if (!$site->isSiteAvailable() && empty($redirectUrl)) {
            $response = new Response();
            $response->setStatusCode(404);
            $event->stopPropagation(true);

            return $response;
        }

        if ($redirectUrl) {
            $response = new Response();
            $response->setStatusCode(302);
            $response->getHeaders()->addHeaderLine('Location', '//' . $redirectUrl);

            $event->stopPropagation(true);

            return $response;
        }

        $redirectUrl = $this->domainRedirectService->getPrimaryRedirectUrl($site);

        if ($redirectUrl) {
            $response = new Response();
            $response->setStatusCode(302);
            $response->getHeaders()->addHeaderLine('Location', '//' . $redirectUrl);

            $event->stopPropagation(true);

            return $response;
        }

        return null;
    }

    /**
     * Check the defined redirects.  If requested URL is found, redirect to the
     * new location.
     *
     * @param MvcEvent $event Zend MVC Event
     *
     * @return null|Response
     */
    public function checkRedirect(MvcEvent $event)
    {
        if ($this->isConsoleRequest()) {
            return null;
        }

        // User defaults
        $redirectUrl = $this->redirectService->getRedirectUrl();

        if (empty($redirectUrl)) {
            return null;
        }


        $queryParams = $event->getRequest()->getQuery()->toArray();

        header(
            'Location: ' . $redirectUrl . (count($queryParams) ? '?' . http_build_query($queryParams) : null),
            true,
            302
        );
        exit;

        /* Below is the ZF2 way but Response is not short-circuiting the event like it should *
        $response = new Response();
        $response->setStatusCode(302);
        $response->getHeaders()
            ->addHeaderLine(
                'Location',
                $redirect->getRedirectUrl()
            );
        $event->stopPropagation(true);

        return $response;
        */
    }

    /**
     * Set the system locale to Site Requirements
     *
     * @param MvcEvent $event
     *
     * @return null
     */
    public function addLocale(MvcEvent $event)
    {
        $locale = $this->siteService->getCurrentSite()->getLocale();

        $this->localeService->setLocale($locale);

        return null;
    }
}
