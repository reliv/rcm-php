<?php

namespace Rcm\Acl;

use Rcm\Entity\Page;
use Rcm\Entity\Site;
use RcmUser\Service\RcmUserService;

class CmsPermissionChecks
{
    /** @var  \RcmUser\Service\RcmUserService */
    protected $rcmUserService;

    public function __construct(RcmUserService $rcmUserService)
    {
        $this->rcmUserService = $rcmUserService;
    }

    public function isPageAllowedForReading(Page $page)
    {
        $allowed = $this->rcmUserService->isAllowed(
            'sites.' . $page->getSite()->getSiteId() . '.pages.' . $page->getPageType() . '.' . $page->getName(),
            'read',
            'Rcm\Acl\ResourceProvider'
        );

        $path = '/'.$page->getName();
        $siteLoginPage = $page->getSite()->getLoginPage();
        $notAuthorizedPage = $page->getSite()->getNotAuthorizedPage();
        $notFoundPage = $page->getSite()->getNotFoundPage();

        if ($siteLoginPage == $path
            || $notAuthorizedPage == $path
            || $notFoundPage == $path
        ) {
            $allowed = true;
        }

        return $allowed;
    }

    public function siteAdminCheck(Site $site)
    {
        return $this->rcmUserService->isAllowed(
            'sites.' . $site->getSiteId(),
            'admin',
            'Rcm\Acl\ResourceProvider'
        );
    }

    /**
     * Check to make sure user can see revisions
     *
     * @return bool
     */
    public function shouldShowRevisions($siteId, $pageType, $pageName)
    {
        $allowedRevisions = $this->rcmUserService->isAllowed(
            'sites.' . $siteId . '.pages.' . $pageType . '.' . $pageName,
            'edit',
            'Rcm\Acl\ResourceProvider'
        );

        if ($allowedRevisions) {
            return true;
        }

        $allowedRevisions = $this->rcmUserService->isAllowed(
            'sites.' . $siteId . '.pages.' . $pageType . '.' . $pageName,
            'approve',
            'Rcm\Acl\ResourceProvider'
        );

        if ($allowedRevisions) {
            return true;
        }

        $allowedRevisions = $this->rcmUserService->isAllowed(
            'sites.' . $siteId . '.pages.' . $pageType . '.' . $pageName,
            'revisions',
            'Rcm\Acl\ResourceProvider'
        );

        if ($allowedRevisions) {
            return true;
        }

        $allowedRevisions = $this->rcmUserService->isAllowed(
            'sites.' . $siteId . '.pages',
            'create',
            'Rcm\Acl\ResourceProvider'
        );

        if ($allowedRevisions) {
            return true;
        }

        return false;
    }
}