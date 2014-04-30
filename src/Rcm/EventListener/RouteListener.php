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
    protected $domainManager;

    /**
     * Constructor
     *
     * @param DomainManager $domainManager Rcm Domain Manager
     */
    public function __construct(DomainManager $domainManager)
    {
        $this->domainManager = $domainManager;
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
        $domainList = $this->domainManager->getDomainList();

        /** @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $event->getRequest();

        $serverParam = $request->getServer();
        $currentDomain = $serverParam->get('HTTP_HOST');

        if (empty($domainList[$currentDomain])) {
            $response = new Response();
            $response->setStatusCode(404);
            $event->stopPropagation(true);
            return $response;
        }

        if (!empty($domainList[$currentDomain]['primaryDomain'])) {
            $response = new Response();
            $response->setStatusCode(302);
            $response->getHeaders()
                ->addHeaderLine(
                    'Location',
                    '//'.$domainList[$currentDomain]['primaryDomain']
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

        $redirectList = $this->domainManager->getRedirectList();

        $requestUrl = $httpHost.$requestUri;

        if (!empty($redirectList[$requestUrl])) {
            $response = new Response();
            $response->setStatusCode(302);
            $response->getHeaders()
                ->addHeaderLine(
                    'Location', '//'.$redirectList[$requestUrl]['redirectUrl']
                );
            $event->stopPropagation(true);
            return $response;
        }

        return null;
    }
}