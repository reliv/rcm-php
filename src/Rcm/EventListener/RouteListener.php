<?php
/**
 * RCM Route Listener
 *
 * Route Listener for Zend Event "route"
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace Rcm\EventListener;

use Rcm\Service\DomainManager;
use Rcm\Service\RedirectManager;
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
    /** @var \Rcm\Service\DomainManager */
    protected $domainManager;

    /** @var \Rcm\Service\RedirectManager */
    protected $redirectManager;

    /**
     * Constructor
     *
     * @param DomainManager   $domainManager   Rcm Domain Manager
     * @param RedirectManager $redirectManager Rcm Redirect Manager
     */
    public function __construct(
        DomainManager $domainManager,
        RedirectManager $redirectManager
    ) {
        $this->domainManager = $domainManager;
        $this->redirectManager = $redirectManager;
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

        $serviceManager = $event->getApplication()->getServiceManager();

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $serviceManager->get('RcmCurrentSite');

        if (empty($currentSite->getSiteId())) {
            $response = new Response();
            $response->setStatusCode(404);
            $event->stopPropagation(true);

            return $response;
        }

        $primary = $currentSite->getDomain()->getPrimary();

        if (!empty($primary)) {
            $response = new Response();
            $response->setStatusCode(302);
            $response->getHeaders()
                ->addHeaderLine(
                    'Location',
                    '//' . $primary
                );

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

        /** @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $event->getRequest();

        $serverParam = $request->getServer();
        $httpHost = $serverParam->get('HTTP_HOST');
        $requestUri = $serverParam->get('REQUEST_URI');

        $redirectList = $this->redirectManager->getRedirectList();

        $requestUrl = $httpHost . $requestUri;

        if (!empty($redirectList[$requestUrl])) {
            $response = new Response();
            $response->setStatusCode(302);
            $response->getHeaders()
                ->addHeaderLine(
                    'Location',
                    '//' . $redirectList[$requestUrl]['redirectUrl']
                );
            $event->stopPropagation(true);

            return $response;
        }

        return null;
    }

    public function addLocale(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();

        $siteInfo = $serviceManager->get('Rcm\Service\SiteManager')
            ->getCurrentSiteInfo();
        $locale = $siteInfo['language']['iso639_1'] . '_'
            . $siteInfo['country']['iso2'];

        setlocale(
            LC_ALL,
            $locale
        );
        \Locale::setDefault($locale);

        return null;
    }
}
