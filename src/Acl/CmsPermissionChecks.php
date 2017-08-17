<?php

namespace Rcm\Acl;

use Rcm\Entity\Page;
use Rcm\Entity\Site;
use RcmUser\Service\RcmUserService;

/**
 * Class CmsPermissionChecks
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Acl
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class CmsPermissionChecks
{
    /** @var  \RcmUser\Service\RcmUserService */
    protected $rcmUserService;

    /**
     * @var ResourceName
     */
    protected $resourceName;

    /**
     * @param RcmUserService $rcmUserService
     * @param ResourceName   $resourceName
     */
    public function __construct(
        RcmUserService $rcmUserService,
        ResourceName $resourceName
    ) {
        $this->rcmUserService = $rcmUserService;
        $this->resourceName = $resourceName;
    }

    /**
     * getAclDataService
     *
     * @return \RcmUser\Acl\Service\AclDataService
     */
    protected function getAclDataService()
    {
        return $this->rcmUserService->getAuthorizeService()->getAclDataService();
    }

    /**
     * isPageAllowedForReading
     *
     * @param Page $page
     *
     * @return bool
     */
    public function isPageAllowedForReading(Page $page)
    {
        $allowed = $this->rcmUserService->isAllowed(
            $this->buildPageResourceId(
                $page->getSite()->getSiteId(),
                $page->getPageType(),
                $page->getName()
            ),
            'read',
            \Rcm\Acl\ResourceProvider::class
        );

        /* ltrim added for BC */
        $currentPage = $page->getName();
        $siteLoginPage = ltrim($page->getSite()->getLoginPage(), '/');
        $notAuthorizedPage = ltrim($page->getSite()->getNotAuthorizedPage(), '/');
        $notFoundPage = ltrim($page->getSite()->getNotFoundPage(), '/');

        if ($siteLoginPage == $currentPage
            || $notAuthorizedPage == $currentPage
            || $notFoundPage == $currentPage
        ) {
            $allowed = true;
        }

        return $allowed;
    }

    /**
     * siteAdminCheck
     *
     * @param Site $site
     *
     * @return bool
     */
    public function siteAdminCheck(Site $site)
    {
        return $this->rcmUserService->isAllowed(
            $this->buildSiteResourceId(
                $site->getSiteId()
            ),
            'admin',
            \Rcm\Acl\ResourceProvider::class
        );
    }

    /**
     * hasRoleBasedAccess
     *
     * @param $role
     *
     * @return bool
     */
    public function hasRoleBasedAccess($role)
    {
        return $this->rcmUserService->hasRoleBasedAccess($role);
    }

    /**
     * isCurrentUserLoggedIn
     *
     * @return bool
     */
    public function isCurrentUserLoggedIn()
    {
        return $this->rcmUserService->hasIdentity();
    }

    /**
     * Check to make sure user can see revisions
     *
     * @return bool
     */
    public function shouldShowRevisions($siteId, $pageType, $pageName)
    {
        $allowedRevisions = $this->rcmUserService->isAllowed(
            $this->buildPageResourceId(
                $siteId,
                $pageType,
                $pageName
            ),
            'edit',
            \Rcm\Acl\ResourceProvider::class
        );

        if ($allowedRevisions) {
            return true;
        }

        $allowedRevisions = $this->rcmUserService->isAllowed(
            $this->buildPageResourceId(
                $siteId,
                $pageType,
                $pageName
            ),
            'approve',
            \Rcm\Acl\ResourceProvider::class
        );

        if ($allowedRevisions) {
            return true;
        }

        $allowedRevisions = $this->rcmUserService->isAllowed(
            $this->buildPageResourceId(
                $siteId,
                $pageType,
                $pageName
            ),
            'revisions',
            \Rcm\Acl\ResourceProvider::class
        );

        if ($allowedRevisions) {
            return true;
        }

        $allowedRevisions = $this->rcmUserService->isAllowed(
            $this->buildPagesResourceId(
                $siteId
            ),
            'create',
            \Rcm\Acl\ResourceProvider::class
        );

        if ($allowedRevisions) {
            return true;
        }

        return false;
    }

    /**
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
        $resourceId = $this->buildPageResourceId($siteId, $pageType, $pageName);

        $aclDataService = $this->getAclDataService();

        //getting all set rules by resource Id
        $rules = $aclDataService->getRulesByResourcePrivilege(
            $resourceId,
            $privilege
        )->getData();

        if (empty($rules)) {
            return false;
        }

        return true;
    }

    /**
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
