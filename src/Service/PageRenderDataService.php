<?php

namespace Rcm\Service;

use Doctrine\ORM\EntityManager;
use Rcm\Acl\CmsPermissionChecks;
use Rcm\Entity\Page;
use Rcm\Entity\PageRenderData;
use Rcm\Entity\Site;
use Rcm\Exception\PageNotFoundException;

/**
 * Class PageRenderDataService
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class PageRenderDataService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var CmsPermissionChecks
     */
    protected $cmsPermissionChecks;

    /**
     * @var PageStatus
     */
    protected $pageStatus;

    /**
     * Constructor.
     *
     * @param EntityManager       $entityManager
     * @param CmsPermissionChecks $cmsPermissionChecks
     * @param PageStatus          $pageStatus
     */
    public function __construct(
        EntityManager $entityManager,
        CmsPermissionChecks $cmsPermissionChecks,
        PageStatus $pageStatus
    ) {
        $this->entityManager = $entityManager;
        $this->cmsPermissionChecks = $cmsPermissionChecks;
        $this->pageStatus = $pageStatus;
    }

    /**
     * getEntityManager
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * getPageRepository
     *
     * @return \Rcm\Repository\Page
     */
    protected function getPageRepository()
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->getRepository(Page::class);
    }

    /**
     * getNew
     *
     * @return PageRenderData
     */
    public function getNew()
    {
        return new PageRenderData();
    }

    /**
     * getData
     *
     * @param Site $site
     * @param Page $page
     * @param null $revisionId
     *
     * @return PageRenderData
     */
    public function getData(
        Site $site,
        Page $page,
        $revisionId = null
    ) {
        $pageRenderData = $this->getNew();
        $pageRenderData->setSite($site);
        $pageRenderData->setRequestedPage(
            [
                'name' => strtolower($page->getName()),
                'type' => strtolower($page->getPageType()),
                'revision' => $revisionId,
            ]
        );

        $page = $this->preparePage(
            $site,
            $page,
            $revisionId
        );

        if (empty($page)) {
            $pageRenderData->setHttpStatus(
                $this->pageStatus->getNotFoundStatus()
            );

            return $pageRenderData;
        }

        $allowed = $this->cmsPermissionChecks->isPageAllowedForReading($page);

        if (!$allowed) {
            $pageRenderData->setHttpStatus(
                $this->pageStatus->getNotAuthorizedStatus()
            );

            return $pageRenderData;
        }

        $pageRenderData->setPage($page);

        // @todo Insert Block data (plugin data)
        // $pageRenderData->setBlocks([]);

        $pageRenderData->setHttpStatus(
            $this->getStatus(
                $pageRenderData->getRequestedPageName(),
                $page->getName()
            )
        );

        return $pageRenderData;
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
     * @param int|null  $revisionId
     *
     * @return null|Page
     */
    protected function preparePage(
        Site $site,
        $page,
        $revisionId = null
    ) {
        if (empty($page)) {
            $page = $this->getNotFoundPage($site);
        }

        $revision = $this->getRevision($site, $page, $revisionId);

        if (empty($revision)) {
            $revision = $page->getCurrentRevision();
        }

        if (empty($revision)) {
            $page = $this->getNotFoundPage($site);
            $revision = $page->getCurrentRevision();//$this->getRevision($site, $page);
        }

        if (empty($page)) {
            return null;
        }

        if (empty($revision)) {
            throw new PageNotFoundException(
                'No revision found for page name: ' .
                json_encode($page->getName()) . ' id: ' .
                json_encode($page->getPageId()) . ' type: ' .
                json_encode($page->getPageType())
            );
        }

        $page->setCurrentRevision($revision);

        return $page;
    }

    /**
     * getNotFoundPage
     *
     * @param Site $site
     *
     * @return mixed
     */
    protected function getNotFoundPage(
        Site $site
    ) {
        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $this->getPageRepository();

        $page = $pageRepo->getPageByName(
            $site,
            $site->getNotFoundPage(),
            'n'
        );

        if (empty($page)) {
            throw new PageNotFoundException(
                'No default page defined for 404 not found error'
            );
        }

        return $page;
    }
}
