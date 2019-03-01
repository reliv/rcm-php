<?php

namespace RcmAdmin\Controller;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Entity\Site;
use Rcm\ImmutableHistory\VersionRepositoryInterface;
use RcmAdmin\Service\SiteManager;
use RcmUser\Api\Authentication\GetIdentity;
use Zend\Diactoros\Response\JsonResponse;
use \Zend\Http\Response;
use \Zend\Mvc\Controller\AbstractRestfulController;
use RcmUser\Api\Acl\IsAllowed;

class SiteDomainNameController implements MiddlewareInterface
{
    protected $currentSite;

    protected $isAllowed;

    protected $siteManager;

    protected $getIdentity;

    public function __construct(
        Site $currentSite,
        IsAllowed $isAllowed,
        SiteManager $siteManager,
        GetIdentity $getIdentity
    ) {
        $this->currentSite = $currentSite;
        $this->isAllowed = $isAllowed;
        $this->siteManager = $siteManager;
        $this->getIdentity = $getIdentity;
    }

    /**
     * @example of changing the domain of the current website:
     * curl -X PUT local.reliv.com:3000/api/rcm/site/current/domain \
     * --data '{"host":"bob.local.reliv.com"}' \
     * --header "Content-Type: application/json;Cookie: rel" \
     * --header "Cookie: reliv_session_id_local=HUMAN_PUT_YOUR_COOKIE_VALUE_HERE"
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface|JsonResponse
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $user = $this->getIdentity->__invoke($request);

        if (!$this->isAllowed->__invoke($request, 'sites', 'admin')
            || !$user
            || !$user->getId()
        ) {
            return new JsonResponse(['error' => 'uauthorized'], 401);
        } //@TODO!
        $body = $request->getParsedBody();
        if (!isset($body['host'])) {
            return new JsonResponse(['error' => '"host" field is required'], 401);
        }
        $this->siteManager->changeSiteDomainName($this->currentSite, $body['host'], $user->getId());

        return new JsonResponse(['host' => $this->currentSite->getDomain()->getDomainName()]);
    }
}
