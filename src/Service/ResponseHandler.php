<?php
/**
 * Rcm Response Handler
 *
 * This file contains the class definition for the Response Handler
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace Rcm\Service;

use Rcm\Entity\Site;
use Rcm\Http\Response;
use RcmUser\Service\RcmUserService;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\ResponseSender\HttpResponseSender;
use Zend\Mvc\ResponseSender\SendResponseEvent;
use Zend\Stdlib\RequestInterface;
use Zend\Stdlib\ResponseInterface;

/**
 * Rcm Response Handler
 *
 * Rcm Response Handler.  This class is designed to handle all RCM Response
 * objects.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class ResponseHandler
{
    /** @var \Zend\Http\PhpEnvironment\Request */
    protected $request;

    /** @var \Rcm\Entity\Site */
    protected $currentSite;

    /** @var \Zend\Mvc\ResponseSender\HttpResponseSender */
    protected $responseSender;

    /** @var bool */
    protected $terminate = true;

    /** @var RcmUserService  */
    protected $userService;

    /**
     * Constructor
     *
     * @param RequestInterface   $request        Zend Request Object
     * @param Site               $currentSite    Rcm Site Manager
     * @param HttpResponseSender $responseSender Zend Http Response Sender.
     * @param RcmUserService     $rcmUserService Rcm User Service
     */
    public function __construct(
        RequestInterface $request,
        Site $currentSite,
        HttpResponseSender $responseSender,
        RcmUserService $rcmUserService
    ) {
        $this->request        = $request;
        $this->currentSite    = $currentSite;
        $this->responseSender = $responseSender;
        $this->userService    = $rcmUserService;
    }

    /**
     * Process Rcm Response objects or simply return if response is a normal
     * Zend Response.
     *
     * @param ResponseInterface $response Zend Response Object.
     *
     * @return void
     */
    public function processResponse(ResponseInterface $response)
    {
        if (!$response instanceof Response
            || !$this->getRequest()
        ) {
            return;
        }

        $response = $this->handleResponse($response);
        $this->renderResponse($response);
        $this->terminateApp();
    }

    /**
     * Handle the RCM Response object
     *
     * @param Response $response Rcm Response Object
     *
     * @return Response
     */
    protected function handleResponse(Response $response)
    {
        $statusCode = $response->getStatusCode();

        switch ($statusCode) {
            case 401:
                $response = $this->processNotAuthorized();
                break;
        }

        return $response;
    }

    /**
     * Process 401 Response Objects.  This will redirect the visitor to the
     * sites configured login page.
     *
     * @return Response
     */
    protected function processNotAuthorized()
    {
        $loginPage = $this->currentSite->getLoginPage();
        $notAuthorized = $this->currentSite->getNotAuthorizedPage();
        $returnToUrl = urlencode($this->request->getServer('REQUEST_URI'));
        $newResponse = new Response();
        $newResponse->setStatusCode('302');

        if (!$this->userService->hasIdentity()) {
            $newResponse->getHeaders()
                ->addHeaderLine(
                    'Location: ' . $loginPage . '?redirect=' . $returnToUrl
                );
        } else {
            $newResponse->getHeaders()
                ->addHeaderLine(
                    'Location: ' . $notAuthorized
                );
        }

        return $newResponse;
    }

    /**
     * Renders the Response object.
     *
     * @param Response $response Rcm Response Object
     *
     * @return void
     */
    protected function renderResponse(Response $response)
    {
        $sendEvent = new SendResponseEvent();
        $sendEvent->setResponse($response);

        $this->responseSender->__invoke($sendEvent);
    }

    /**
     * Terminate the app.  This is needed after rendering the response to short
     * circuit ZF.  Can be set to non-terminate for testing purposes.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.ExitExpression)
     * @codeCoverageIgnore
     */
    protected function terminateApp()
    {
        if ($this->terminate) {
            exit;
        }
    }

    /**
     * Sets the terminate flag.
     *
     * @param boolean $terminate Set the terminate flag.
     *
     * @return void
     */
    public function setTerminate($terminate)
    {
        $this->terminate = $terminate;
    }

    /**
     * Gets the Zend Http Request or returns null if not found or contains a
     * different type of request object.
     *
     * @return null|Request
     */
    public function getRequest()
    {
        if (!$this->request instanceof Request) {
            return null;
        }

        return $this->request;
    }
}
