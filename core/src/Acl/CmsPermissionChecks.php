<?php

namespace Rcm\Acl;

use Rcm\Api\Acl\IsAllowedShowRevisions;
use Rcm\Api\Acl\IsAllowedSiteAdmin;
use Rcm\Api\Acl\IsPageAllowedForReading;
use Rcm\Api\Acl\IsPageRestricted;
use Rcm\Api\Acl\IsUserLoggedIn;
use Rcm\Api\GetPsrRequest;
use Rcm\Entity\Page;
use Rcm\Entity\Site;

/**
 * @deprecated Use \Rcm\Api\Acl\*
 */
class CmsPermissionChecks
{
    protected $resourceName;
    protected $isPageAllowedForReading;
    protected $isAllowedSiteAdmin;
    protected $hasRoleBasedAccess;
    protected $isUserLoggedIn;
    protected $isAllowedShowRevisions;
    protected $isPageRestricted;

    /**
     * @param ResourceName            $resourceName
     * @param IsPageAllowedForReading $isPageAllowedForReading
     * @param IsAllowedSiteAdmin      $isAllowedSiteAdmin
     * @param IsUserLoggedIn          $isUserLoggedIn
     * @param IsAllowedShowRevisions  $isAllowedShowRevisions
     * @param IsPageRestricted        $isPageRestricted
     */
    public function __construct(
        ResourceName $resourceName,
        IsPageAllowedForReading $isPageAllowedForReading,
        IsAllowedSiteAdmin $isAllowedSiteAdmin,
        IsUserLoggedIn $isUserLoggedIn,
        IsAllowedShowRevisions $isAllowedShowRevisions,
        IsPageRestricted $isPageRestricted
    ) {
        $this->resourceName = $resourceName;
        $this->isPageAllowedForReading = $isPageAllowedForReading;
        $this->isAllowedSiteAdmin = $isAllowedSiteAdmin;
        $this->isUserLoggedIn = $isUserLoggedIn;
        $this->isAllowedShowRevisions = $isAllowedShowRevisions;
        $this->isPageRestricted = $isPageRestricted;
    }

    /**
     * @deprecated Use \Rcm\Api\Acl\IsPageAllowedForReading
     * isPageAllowedForReading
     *
     * @param Page $page
     *
     * @return bool
     */
    public function isPageAllowedForReading(Page $page)
    {
        return $this->isPageAllowedForReading->__invoke(
            GetPsrRequest::invoke(),
            $page
        );
    }

    /**
     * @deprecated Use \Rcm\Api\Acl\IsAllowedSiteAdmin
     * siteAdminCheck
     *
     * @param Site $site
     *
     * @return bool
     */
    public function siteAdminCheck(Site $site)
    {
        return $this->isAllowedSiteAdmin->__invoke(
            GetPsrRequest::invoke(),
            $site
        );
    }

    /**
     * @deprecated Use \Rcm\Api\Acl\IsUserLoggedIn
     * isCurrentUserLoggedIn
     *
     * @return bool
     */
    public function isCurrentUserLoggedIn()
    {
        return $this->isUserLoggedIn->__invoke(
            GetPsrRequest::invoke()
        );
    }

    /**
     * @deprecated Use \Rcm\Api\Acl\IsAllowedShowRevisions
     * Check to make sure user can see revisions
     *
     * @return bool
     */
    public function shouldShowRevisions($siteId, $pageType, $pageName)
    {
        return $this->isAllowedShowRevisions->__invoke(
            GetPsrRequest::invoke(),
            $siteId,
            $pageType,
            $pageName
        );
    }

    /**
     * @deprecated Use \Rcm\Api\Acl\IsPageRestricted
     * isPageRestricted
     *
     * @param $siteId
     * @param $pageType
     * @param $pageName
     * @param $privilege
     *
     * @return bool
     */
    public function isPageRestricted($siteId, $pageType, $pageName, $privilege)
    {
        return $this->isPageRestricted->__invoke(
            $siteId,
            $pageType,
            $pageName,
            $privilege
        );
    }

    /**
     * @deprecated Use \Rcm\Acl\ResourceName
     * buildSiteResourceId
     *
     * @param $siteId
     *
     * @return string
     */
    public function buildSiteResourceId(
        $siteId
    ) {
        return $this->resourceName->get(
            ResourceName::RESOURCE_SITES,
            $siteId
        );
    }

    /**
     * @deprecated Use \Rcm\Acl\ResourceName
     * buildPagesResourceId
     *
     * @param $siteId
     *
     * @return string
     */
    public function buildPagesResourceId(
        $siteId
    ) {
        return $this->resourceName->get(
            ResourceName::RESOURCE_SITES,
            $siteId,
            ResourceName::RESOURCE_PAGES
        );
    }

    /**
     * @deprecated Use \Rcm\Acl\ResourceName
     * buildResourceId
     *
     * @param $siteId
     * @param $pageType
     * @param $pageName
     *
     * @return string
     */
    public function buildPageResourceId(
        $siteId,
        $pageType,
        $pageName
    ) {
        return $this->resourceName->get(
            ResourceName::RESOURCE_SITES,
            $siteId,
            ResourceName::RESOURCE_PAGES,
            $pageType,
            $pageName
        );
    }
}
