<?php


namespace Rcm\SiteSettingsSections;

use Doctrine\ORM\EntityManager;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Entity\Site;
use RcmUser\Api\Acl\IsAllowed;
use RcmUser\Api\Authentication\GetIdentity;
use Zend\Diactoros\Response\JsonResponse;

class HttpPutSiteSettingsSectionController implements MiddlewareInterface
{
    protected $setSection;
    protected $getSection;
    protected $entityManager;
    protected $currentSite;
    protected $isAllowed;
    protected $getIdentity;

    public function __construct(
        IsAllowed $isAllowed,
        SetSection $setSection,
        GetSection $getSection,
        EntityManager $entityManager,
        Site $currentSite,
        GetIdentity $getIdentity
    ) {
        $this->getIdentity = $getIdentity;
        $this->isAllowed = $isAllowed;
        $this->setSection = $setSection;
        $this->getSection = $getSection;
        $this->entityManager = $entityManager;
        $this->currentSite = $currentSite;
    }

    /**
     * Example Curl:
     * curl -X PUT http://local.reliv.com:3000/api/rcm/site-settings-section/current/bob \
     * --data '{"yolo":false}' -vvv -H "Content-Type: application/json" \
     * -H "Content-Type: application/json" -H "Cookie: reliv_session_id_local=HUMAN_FILL_THIS_IN"
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface|JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        /**
         * @TODO AclByCountryPlanToMoveToNode
         */
        $user = $this->getIdentity->__invoke($request);

        if (!$this->isAllowed->__invoke($request, 'sites', 'admin')
            || !$user
            || !$user->getId()
        ) {
            return new JsonResponse(['error' => 'unauthorized'], 401);
        }

        $sectionName = $request->getAttribute('sectionName');

        /**
         * Note: Ideally the request-body/settings could be validated using a
         * server-side version of field rat in the future.
         *
         * Only admins can send this data and we don't have a server-side version of
         * field rat that validates for each field, so for now this data
         * won't be validated.
         */
        $settings = $request->getParsedBody();

        $site = $this->currentSite;

        try {
            $this->setSection->__invoke($site, $sectionName, $settings, $user->getId());
            $return = $this->getSection->__invoke($site, $sectionName);
        } catch (InvalidSectionNameException $e) {
            return new JsonResponse(['error' => 'invalid section name'], 400);
        }

        return new JsonResponse($return);
    }
}
