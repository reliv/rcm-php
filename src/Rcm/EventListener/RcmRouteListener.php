<?php

namespace Rcm\EventListener;

use Rcm\Service\DomainManager;
use Zend\Http\Response;

class RcmRouteListener
{
    protected $domainManager;

    public function __construct(DomainManager $domainManager)
    {
        $this->domainManager = $domainManager;
    }

    /**
     * @param \Zend\Mvc\MvcEvent $event
     * @return null
     */
    public function checkDomain(\Zend\Mvc\MvcEvent $event)
    {
        $domainList = $this->domainManager->getDomainList();

        $currentDomain = $_SERVER['HTTP_HOST'];

        if (empty($domainList[$currentDomain])) {
            $response = new Response();
            $response->setStatusCode(404);
            $event->stopPropagation(true);
            return $response;
        }

        if (!empty($domainList[$currentDomain]['primaryDomain'])) {
            $response = new Response();
            $response->setStatusCode(302);
            $response->getHeaders()->addHeaderLine('Location', '//'.$domainList[$currentDomain]['primaryDomain']);
            $event->stopPropagation(true);
            return $response;
        }

        return null;

    }

    public function checkRedirect($event)
    {
        $redirectList = $this->domainManager->getRedirectList();

        $requestUrl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        if (!empty($redirectList[$requestUrl])) {
            $response = new Response();
            $response->setStatusCode(302);
            $response->getHeaders()->addHeaderLine('Location', '//'.$redirectList[$requestUrl]['redirectUrl']);
            $event->stopPropagation(true);
            return $response;
        }

        return null;
    }



}