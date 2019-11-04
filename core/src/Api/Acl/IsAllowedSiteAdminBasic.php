<?php

namespace Rcm\Api\Acl;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Acl\AclActions;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Acl\NotAllowedException;
use Rcm\Acl\ResourceName;
use Rcm\Acl2\SecurityPropertyConstants;
use Rcm\Api\GetPsrRequest;
use Rcm\Entity\Site;

class IsAllowedSiteAdminBasic implements IsAllowedSiteAdmin
{
    protected $assertIsAllowed;

    public function __construct(
        ContainerInterface $requestContext
    ) {
        $this->assertIsAllowed = $requestContext->get(AssertIsAllowed::class);
    }

    /**
     * @param ServerRequestInterface $request
     * @param Site $site
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        Site $site
    ): bool {
        /** @oldAclAccessCheckReplaced */

        try {
            $this->assertIsAllowed->__invoke(
                AclActions::UPDATE,
                [
                    'type' => SecurityPropertyConstants::TYPE_CONTENT,
                    SecurityPropertyConstants::CONTENT_TYPE_KEY
                    => SecurityPropertyConstants::CONTENT_TYPE_SITE,
                    'country' => $site->getCountryIso3()
                ]
            );
        } catch (NotAllowedException $e) {
            return false;
        }

        return true;
    }
}
