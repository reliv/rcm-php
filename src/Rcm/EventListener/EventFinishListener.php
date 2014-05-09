<?php
/**
 * RCM Event Finish Listener
 *
 * Event Finish Listener for Zend Event "dispatch"
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

use Rcm\Service\SiteManager;
use Rcm\Http\Response as RcmResponse;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\MvcEvent;

/**
 * RCM Event Finish Listener
 *
 * This Event Finish listener will handle any custom http responses for the CMS.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class EventFinishListener
{
    /** @var \Rcm\Service\SiteManager  */
    protected $siteManager;

    /**
     * Constructor
     *
     * @param SiteManager $siteManager Rcm Site Manager
     */
    public function __construct(
        SiteManager $siteManager
    ) {
        $this->siteManager = $siteManager;
    }

    /**
     * Check for not authorized and redirect to the login page on 401.
     *
     * @param MvcEvent $event Zend MVC Event object
     *
     * @return void
     */

    public function checkForNotAuthorized(MvcEvent $event)
    {
        $response = $event->getResult();
        if (!$response instanceof RcmResponse) {
            return;
        }

        // Only handle 401 responses
        if ($response->getStatusCode() != 401) {
            return;
        }

        /** @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $event->getRequest();

        $loginPage = $this->siteManager->getCurrentSiteLoginPage();
        $returnToUrl = urlencode($request->getServer('REQUEST_URI'));

        $newResponse = new HttpResponse();
        $newResponse->setStatusCode('302');
        $newResponse->getHeaders()
            ->addHeaderLine('Location: '.$loginPage.'?redirect='.$returnToUrl);

        $event->setResponse($newResponse);

    }

}
