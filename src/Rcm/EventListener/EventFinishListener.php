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

use Rcm\Http\Response as RcmResponse;
use Rcm\Service\ResponseHandler;
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
    /** @var \Rcm\Service\ResponseHandler */
    protected $responseHandler;

    /**
     * Constructor
     *
     * @param ResponseHandler $responseHandler Rcm Response Handler
     */
    public function __construct(ResponseHandler $responseHandler)
    {
        $this->responseHandler = $responseHandler;
    }

    /**
     * Check for not authorized and redirect to the login page on 401.
     *
     * @param MvcEvent $event Zend MVC Event object
     *
     * @return null
     */

    public function processRcmResponses(MvcEvent $event)
    {
        $response = $event->getResult();

        if (!$response instanceof RcmResponse) {
            return null;
        }

        $this->responseHandler->processResponse($response);

        return null;
    }
}
