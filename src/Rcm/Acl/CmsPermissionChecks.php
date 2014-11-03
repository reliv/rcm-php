<?php

namespace Rcm\Acl;

use Rcm\Entity\Page;
use Rcm\Entity\Site;
use RcmUser\Service\RcmUserService;

/**
 * Class CmsPermissionChecks
 *
 * LongDescHere
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
     * @param RcmUserService $rcmUserService
     */
    public function __construct(RcmUserService $rcmUserService)
    {
        $this->rcmUserService = $rcmUserService;
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
            'Rcm\Acl\ResourceProvider'
        );

        $path = '/' . $page->getName();
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
            $this->buildPageResourceId(
                $siteId,
                $pageType,
                $pageName
            ),
            'edit',
            'Rcm\Acl\ResourceProvider'
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
            'Rcm\Acl\ResourceProvider'
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
            'Rcm\Acl\ResourceProvider'
        );

        if ($allowedRevisions) {
            return true;
        }

        $allowedRevisions = $this->rcmUserService->isAllowed(
            $this->buildPagesResourceId(
                $siteId
            ),
            'create',
            'Rcm\Acl\ResourceProvider'
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
        $rules = $aclDataService->getRulesByResourcePrivilege($resourceId, $privilege)
            ->getData();

        if (empty($rules)){
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

        return 'sites.' .
        $siteId;
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

        return 'sites.' .
        $siteId .
        '.pages';
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

        return 'sites.' .
        $siteId .
        '.pages.' .
        $pageType .
        '.' .
        $pageName;
    }


}