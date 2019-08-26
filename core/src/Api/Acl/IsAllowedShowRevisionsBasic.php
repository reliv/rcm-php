<?php

namespace Rcm\Api\Acl;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Acl\AclActions;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Acl\NotAllowedException;
use Rcm\Acl\ResourceName;
use Rcm\Acl2\SecurityPropertyConstants;
use Rcm\Api\GetPsrRequest;
use Rcm\Entity\Site;
use Rcm\RequestContext\RequestContext;
use RcmUser\Api\Acl\IsAllowed;

class IsAllowedShowRevisionsBasic implements IsAllowedShowRevisions
{
    protected $entityManager;
    protected $assertIsAllowed;

    public function __construct(
        EntityManager $entityManager,
        ContainerInterface $requestContext
    ) {
        $this->entityManager = $entityManager;
        $this->assertIsAllowed = $requestContext->get(AssertIsAllowed::class);
    }

    /**
     * @param int|string $siteId
     * @param string $pageType
     * @param string $pageName
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        $siteId,
        string $pageType,
        string $pageName
    ): bool {

        $site = $this->entityManager->getRepository(Site::class)->find($siteId);

        if (!$site) {
            return false;
        }

        /** @oldAclAccessCheckReplaced */

        try {
            $this->assertIsAllowed->__invoke(
                AclActions::UPDATE,
                [
                    'type' => SecurityPropertyConstants::TYPE_CONTENT,
                    'country' => $site->getCountryIso3(),
                    SecurityPropertyConstants::CONTENT_TYPE_PAGE_HISTORY
                ]
            );
        } catch (NotAllowedException $e) {
            return false;
        }

        return true;
    }
}
