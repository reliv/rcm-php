<?php

namespace Rcm\Page\PageData;

use Rcm\Acl\CmsPermissionChecks;
use Rcm\Api\Repository\Page\FindPage;
use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Exception\PageNotFoundException;
use Rcm\Exception\RevisionNotFoundException;
use Rcm\Page\PageStatus\PageStatus;
use Rcm\Page\PageTypes\PageTypes;

/**
 * @GammaRelease
 * Class PageDataService
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class PageDataService
{
    /**
     * @var FindPage
     */
    protected $findPage;

    /**
     * @var CmsPermissionChecks
     */
    protected $cmsPermissionChecks;

    /**
     * @var PageStatus
     */
    protected $pageStatus;

    /**
     * @param FindPage            $findPage
     * @param CmsPermissionChecks $cmsPermissionChecks
     * @param PageStatus          $pageStatus
     */
    public function __construct(
        FindPage $findPage,
        CmsPermissionChecks $cmsPermissionChecks,
        PageStatus $pageStatus
    ) {
        $this->findPage = $findPage;
        $this->cmsPermissionChecks = $cmsPermissionChecks;
        $this->pageStatus = $pageStatus;
    }

    /**
     * getNew
     *
     * @return PageDataBc
     */
    public function getNew()
    {
        return new PageDataBc();
    }

    /**
     * getData
     *
     * @param Site   $site
     * @param        $pageName
     * @param string $pageType
     * @param null   $revisionId
     *
     * @return PageData
     */
    public function getData(
        Site $site,
        $pageName,
        $pageType = PageTypes::NORMAL,
        $revisionId = null
    ) {
        $pageData = $this->getNew();
        $pageData->setSite($site);
        $pageData->setRequestedPage(
            [
                'name' => strtolower($pageName),
                'type' => strtolower($pageType),
                'revision' => $revisionId,
            ]
        );

        $requestPage = $this->getPage(
            $site,
            $pageName,
            $pageType
        );

        $page = $this->preparePage(
            $site,
            $requestPage,
            $revisionId
        );

        if (empty($page)) {
            $pageData->setHttpStatus(
                $this->pageStatus->getNotFoundStatus()
            );

            return $pageData;
        }

        $pageData->setPage($page);

        $allowed = $this->cmsPermissionChecks->isPageAllowedForReading($page);

        if (!$allowed) {
            $pageData->setHttpStatus(
                $this->pageStatus->getNotAuthorizedStatus()
            );

            return $pageData;
        }

        // @todo FUTURE Insert Block data (plugin data)
        // $pageData->setBlocks([]);

        $pageData->setHttpStatus(
            $this->getStatus(
                $pageData->getRequestedPageName(),
                $page->getName()
            )
        );

        return $pageData;
    }

    /**
     * getPage
     *
     * @param Site $site
     * @param      $pageName
     * @param      $pageType
     *
     * @return null|Page
     */
    protected function getPage(
        Site $site,
        $pageName,
        $pageType
    ) {
        if (empty($site) || !$site->getSiteId()) {
            return null;
        }

        /* Get the Page for display */
        $page = $this->findPage->__invoke(
            $site->getSiteId(),
            $pageName,
            $pageType
        );

        return $page;
    }

    /**
     * getStatus
     *
     * @param string $requestPageName
     * @param string $responsePageName
     *
     * @return int
     */
    protected function getStatus($requestPageName, $responsePageName)
    {
        if (!$this->isRequestedPage($requestPageName, $responsePageName)) {
            return $this->pageStatus->getStatus($responsePageName);
        }

        return $this->pageStatus->getOkStatus();
    }

    /**
     * isRequestedPage
     *
     * @param $requestPageName
     * @param $responsePageName
     *
     * @return bool
     */
    protected function isRequestedPage($requestPageName, $responsePageName)
    {
        return ($requestPageName == $responsePageName);
    }

    /**
     * getRevision
     *
     * @param Site $site
     * @param Page $page
     * @param null $pageRevisionId
     *
     * @return null|\Rcm\Entity\Revision
     */
    protected function getRevision(
        Site $site,
        Page $page,
        $pageRevisionId = null
    ) {
        $hasRevisionId = !empty($pageRevisionId);

        $hasAccess = false;

        if ($hasRevisionId) {
            $hasAccess = $this->cmsPermissionChecks->shouldShowRevisions(
                $site->getSiteId(),
                $page->getName(),
                $page->getPageType()
            );
        }

        if ($hasAccess) {
            return $page->getRevisionById($pageRevisionId);
        }

        // Check for staging
        $hasAccess = $this->cmsPermissionChecks->siteAdminCheck(
            $site
        );

        $revision = null;

        if ($hasAccess) {
            // @todo is this right, This means admins can never see published revisions
            $revision = $page->getStagedRevision();
        }

        // There might be cases where there is no staged revision
        // so this check is required
        if (!empty($revision)) {
            return $revision;
        }

        // Finally look for published revision
        return $page->getPublishedRevision();
    }

    /**
     * preparePage
     *
     * @param Site      $site
     * @param Page|null $page
     * @param null      $revisionId
     *
     * @return mixed|null
     * @throws RevisionNotFoundException
     */
    protected function preparePage(
        Site $site,
        $page,
        $revisionId = null
    ) {
        if (empty($page)) {
            $page = $this->getNotFoundPage($site);
            $page->setCurrentRevision($page->getPublishedRevision());

            return $page;
        }

        $revision = $this->getRevision($site, $page, $revisionId);

        if (empty($revision)) {
            $page = $this->getNotFoundPage($site);
            $page->setCurrentRevision($page->getPublishedRevision());

            return $page;
        }

        $page->setCurrentRevision($revision);

        return $page;
    }

    /**
     * getNotFoundPage
     *
     * @param Site $site
     *
     * @return Page
     */
    protected function getNotFoundPage(
        Site $site
    ) {
        $page = $this->findPage->__invoke(
            $site->getSiteId(),
            $site->getNotFoundPage(),
            PageTypes::NORMAL
        );

        if (empty($page)) {
            throw new PageNotFoundException(
                'No default page defined for 404 not found error'
            );
        }

        return $page;
    }
}
