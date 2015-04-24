<?php
/**
 * RCM View Event Listener
 *
 * View Event Listener
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
use Zend\View\Renderer\PhpRenderer;
use Zend\View\ViewEvent;

/**
 * RCM View Event Listener
 *
 * This View Event Listener will handle any Rcm http responses for the CMS.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class ViewEventListener
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
     * @param ViewEvent $event Zend MVC Event object
     *
     * @return null
     */

    public function processRcmResponses(ViewEvent $event)
    {
        $response = $event->getResponse();
        if (!$response instanceof RcmResponse) {
            return null;
        }

        $this->responseHandler->processResponse($response);
        return null;
    }
}
