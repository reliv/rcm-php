<?php

namespace Rcm\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;
use Rcm\Acl\ResourceName;
use Rcm\Api\GetPsrRequest;
use Rcm\Entity\Site;
use RcmUser\Api\Acl\IsAllowed;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedSiteAdminBasic implements IsAllowedSiteAdmin
{
    protected $resourceName;
    protected $isAllowed;

    /**
     * @param ResourceName $resourceName
     * @param IsAllowed    $isAllowed
     */
    public function __construct(
        ResourceName $resourceName,
        IsAllowed $isAllowed
    ) {
        $this->resourceName = $resourceName;
        $this->isAllowed = $isAllowed;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Site                   $site
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        Site $site
    ):bool {
        $resourceId = $this->resourceName->get(
            ResourceName::RESOURCE_SITES,
            $site->getSiteId()
        );

        return $this->isAllowed->__invoke(
            GetPsrRequest::invoke(),
            $resourceId,
            'admin'
        );
    }
}
