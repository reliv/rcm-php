<?php


namespace Rcm\SiteSettingsSections;

use Doctrine\ORM\EntityManager;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Acl\AclActions;
use Rcm\Acl\IsAllowedByUser;
use Rcm\Entity\Site;
use RcmUser\Api\Authentication\GetIdentity;
use Zend\Diactoros\Response\JsonResponse;

class HttpGetSiteSettingsSectionController implements MiddlewareInterface
{
    /** @var GetSection */
    protected $getSection;

    /** @var EntityManager */
    protected $entityManager;

    /** @var Site */
    protected $currentSite;

    protected $isAllowedByUser;

    protected $getIdentity;

    public function __construct(
        IsAllowedByUser $isAllowedByUser,
        GetSection $getSection,
        EntityManager $entityManager,
        Site $currentSite,
        GetIdentity $getIdentity
    ) {
        $this->isAllowedByUser = $isAllowedByUser;
        $this->getSection = $getSection;
        $this->entityManager = $entityManager;
        $this->currentSite = $currentSite;
        $this->getIdentity = $getIdentity;
    }

    /**
     * Exmaple Url:
     * http://local.reliv.com:3000/api/rcm/site-settings-section/current/bob
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return JsonResponse
     */
    public function process(
        ServerRequestInterface $request,
        DelegateInterface $delegate
    ): JsonResponse {
        $user = $this->getIdentity->__invoke($request);

        if (!$user
            || !$user->getId()
            // @TODO We plan to move this to node and off more specific ACL queires eventually
            || !$this->isAllowedByUser->__invoke(AclActions::UPDATE, ['type' => 'content'], $user)
        ) {
            return new JsonResponse(['error' => 'unauthorized'], 401);
        }

        $sectionName = $request->getAttribute('sectionName');

        try {
            $settings = $this->getSection->__invoke(
                $this->currentSite,
                $sectionName
            );
        } catch (InvalidSectionNameException $e) {
            return new JsonResponse(['error' => 'invalid section name'], 400);
        }

        if ($settings === null) {
            return new JsonResponse(['error' => 'settings not found'], 404);
        }

        return new JsonResponse($settings);
    }
}
