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

use Rcm\Entity\Site;
use Rcm\Repository\Redirect as RedirectRepo;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Validator\Ip;

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
    /** @var \Rcm\Repository\Redirect */
    protected $redirectRepo;

    protected $currentSite;

    protected $config;

    protected $ipValidator;

    /**
     * @param Site $currentSite
     * @param RedirectRepo $redirectRepo
     * @param Ip $ipValidator
     * @param              $config
     */
    public function __construct(
        Site $currentSite,
        RedirectRepo $redirectRepo,
        Ip $ipValidator,
        $config
    ) {
        $this->currentSite = $currentSite;
        $this->redirectRepo = $redirectRepo;
        $this->config = $config;
        $this->ipValidator = $ipValidator;
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
        /** @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $event->getRequest();
        $serverParam = $request->getServer();
        $currentDomain = $serverParam->get('HTTP_HOST');

        if (empty($currentDomain)) {
            // We are on CLI
            return null;
        }

        if (!$this->currentSite->getSiteId()
            || $this->currentSite->getStatus() != 'A'
        ) {

            if (empty($this->config['Rcm']['defaultDomain'])
                || $this->config['Rcm']['defaultDomain']
                == $this->currentSite->getDomain()->getDomainName()
            ) {
                $response = new Response();
                $response->setStatusCode(404);
                $event->stopPropagation(true);

                return $response;
            }

            $response = new Response();
            $response->setStatusCode(302);
            $response->getHeaders()
                ->addHeaderLine(
                    'Location',
                    '//' . $this->config['Rcm']['defaultDomain']
                );

            $event->stopPropagation(true);
            return $response;

        }

        $primaryCheck = $this->currentSite->getDomain()->getPrimary();

        /**
         * If the IP is not a domain and is not the primary, redirect to primary
         */
        if (!$this->ipValidator->isValid($currentDomain)
            && !empty($primaryCheck)
            && $primaryCheck->getDomainName() != $currentDomain
        ) {
            $response = new Response();
            $response->setStatusCode(302);
            $response->getHeaders()
                ->addHeaderLine(
                    'Location',
                    '//' . $primaryCheck->getDomainName()
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
        $siteId = $this->currentSite->getSiteId();

        if (empty($siteId)) {
            return null;
        }

        /** @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $event->getRequest();

        $serverParam = $request->getServer();
        $requestUri = $serverParam->get('REQUEST_URI');
        $baseUri = explode('?', $requestUri);
        $requestUrl = $baseUri[0];

        $redirect = $this->redirectRepo->getRedirect($requestUrl, $siteId);

        if (!empty($redirect)) {
            header('Location: ' . $redirect->getRedirectUrl(), true, 302);
            exit;

            /* Below is the ZF2 way but Response is not short-circuiting the event like it should */
//            $response = new Response();
//            $response->setStatusCode(302);
//            $response->getHeaders()
//                ->addHeaderLine(
//                    'Location',
//                    $redirect->getRedirectUrl()
//                );
//            $event->stopPropagation(true);
//
//            return $response;
        }

        return null;
    }

    /**
     * Set the system locale to Site Requirements
     *
     * @return null
     */
    public function addLocale()
    {
        $locale = $this->currentSite->getLocale();

        /* Conversion for Ubuntu and Mac local settings. */
        if (!setlocale(
            LC_ALL,
            $locale . '.utf8'
        )
        ) {
            if (!setlocale(
                LC_ALL,
                $locale . '.UTF-8'
            )
            ) {
                setlocale(
                    LC_ALL,
                    'en_US.UTF-8'
                );
            }
        }

        \Locale::setDefault($locale);

        return null;
    }
}