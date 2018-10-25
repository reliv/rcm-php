<?php

namespace Rcm\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;
use Rcm\Acl\ResourceName;
use Rcm\Api\GetPsrRequest;
use RcmUser\Api\Acl\IsAllowed;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedShowRevisionsBasic implements IsAllowedShowRevisions
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
     * @param int|string $siteId
     * @param string     $pageType
     * @param string     $pageName
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        $siteId,
        string $pageType,
        string $pageName
    ): bool {
        $resourceId = $this->resourceName->get(
            ResourceName::RESOURCE_SITES,
            $siteId,
            ResourceName::RESOURCE_PAGES,
            $pageType,
            $pageName
        );

        $allowedRevisions = $this->isAllowed->__invoke(
            $request,
            $resourceId,
            'edit'
        );

        if ($allowedRevisions) {
            return true;
        }

        $allowedRevisions = $this->isAllowed->__invoke(
            $request,
            $resourceId,
            'approve'
        );

        if ($allowedRevisions) {
            return true;
        }

        $allowedRevisions = $this->isAllowed->__invoke(
            $request,
            $resourceId,
            'revisions'
        );

        if ($allowedRevisions) {
            return true;
        }

        $pagesResourceId = $this->resourceName->get(
            ResourceName::RESOURCE_SITES,
            $siteId,
            ResourceName::RESOURCE_PAGES
        );

        $allowedRevisions = $this->isAllowed->__invoke(
            $request,
            $pagesResourceId,
            'create'
        );

        if ($allowedRevisions) {
            return true;
        }

        return false;
    }
}
