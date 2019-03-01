<?php

namespace RcmAdmin\Controller;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Entity\Site;
use Rcm\ImmutableHistory\VersionRepositoryInterface;
use RcmAdmin\Service\SiteManager;
use Zend\Diactoros\Response\JsonResponse;
use \Zend\Http\Response;
use \Zend\Mvc\Controller\AbstractRestfulController;
use RcmUser\Api\Acl\IsAllowed;

class SiteDomainNameController implements MiddlewareInterface
{
    protected $currentSite;

    protected $isAllowed;

    protected $siteManager;

    public function __construct(
        Site $currentSite,
        IsAllowed $isAllowed,
        SiteManager $siteManager
    ) {
        $this->currentSite = $currentSite;
        $this->isAllowed = $isAllowed;
        $this->siteManager = $siteManager;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
//        if (!$this->isAllowed->__invoke($request, 'sites', 'admin')) {
//            return new JsonResponse(['error'=>'uauthorized'], 401);
//        } //@TODO!
        $body = $request->getParsedBody();
        if (!isset($body['domain'])) {
            return new JsonResponse(['error' => '"domain" field is required'], 401);
        }
        ddd($body['domain']);
    }
}
