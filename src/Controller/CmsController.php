<?php
/**
 * Index Controller for the entire application
 *
 * This file contains the main controller used for the application.  This
 * should extend from the base class and should need no further modification.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace Rcm\Controller;

use Rcm\Entity\Page;
use Rcm\Entity\Revision;
use Rcm\Entity\Site;
use Rcm\Exception\PageNotFoundException;
use Rcm\Repository\Page as PageRepo;
use Rcm\Service\LayoutManager;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Index Controller for the entire application
 *
 * This is main controller used for the application.  This should extend from
 * the base class located in Rcm and should need no further
 * modification.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @method Response redirectToPage($pageName, $pageType) Redirect to CMS
 *                                                                  Page
 *
 * @method boolean rcmIsAllowed($resource, $action) Is User Allowed
 * @method boolean shouldShowRevisions($siteId, $pageName, $pageType = 'n') Should Show Revisions for pages
 * @method boolean rcmIsSiteAdmin() Is user a CMS admin
 * @method boolean rcmIsPageAllowed(Page $page) Is user allowed to view a page
 */
class CmsController extends AbstractActionController
{
    /** @var \Rcm\Entity\Site */
    protected $currentSite;

    /** @var \Rcm\Service\LayoutManager */
    protected $layoutManager;

    /** @var  \Rcm\Repository\Page */
    protected $pageRepo;

    protected $pageInfo;
    protected $notFound = false;

    /**
     * Constructor
     *
     * @param LayoutManager $layoutManager Layout Manager to get layouts.
     * @param Site          $currentSite   Current Site Entity
     * @param PageRepo      $pageRepo      Rcm Page Repository
     */
    public function __construct(
        LayoutManager $layoutManager,
        Site $currentSite,
        PageRepo $pageRepo
    ) {
        $this->layoutManager = $layoutManager;
        $this->currentSite = $currentSite;
        $this->pageRepo = $pageRepo;
    }

    /**
     * Index Action.  Main action for page in the CMS.
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $page = $this->getEvent()
            ->getRouteMatch()
            ->getParam('page');

        $revision = $this->getEvent()
            ->getRouteMatch()
            ->getParam('revision', null);

        return $this->getCmsResponse(
            $this->currentSite,
            $page,
            $revision
        );
    }

    /**
     * getCmsResponse
     *
     * @param Site   $site
     * @param Page   $page
     * @param null   $revisionId
     *
     * @return \Rcm\Http\Response|ViewModel
     */
    public function getCmsResponse(
        Site $site,
        Page $page,
        $revisionId = null
    ) {
        /**
         * @todo This should be handled better
         * This is for client, so it can tell if this is an error page
         */
        $requestedPageData = [
            'name' => strtolower($page->getName()),
            'type' => strtolower($page->getPageType()),
            'revision' => $revisionId,
        ];

        $viewModel = new ViewModel();

        if (!$page) {
            $page = $this->renderNotFoundPage($site);
        }

        $allowed = $this->rcmIsPageAllowed($page);

        if (!$allowed) {
            return $this->getUnauthorizedResponse();
        }

        $this->prepPageRevisionForDisplay($page, $revisionId);

        // if we have no revision, page is not found
        if (!$page->getCurrentRevision()) {
            $page = $this->renderNotFoundPage($site);
            $this->prepPageRevisionForDisplay($page);
        }

        $this->prepLayoutView(
            $site,
            $page,
            $requestedPageData,
            $page->getSiteLayoutOverride()
        );

        $viewModel->setVariable('page', $page);

        $viewModel->setTemplate(
            'pages/'
            . $this->layoutManager->getSitePageTemplate(
                $site,
                $page->getPageLayout()
            )
        );

        return $viewModel;
    }

    /**
     * renderNotFoundPage
     *
     * @param Site $site
     *
     * @return null|Page
     */
    public function renderNotFoundPage($site)
    {
        $page = $this->pageRepo->getPageByName(
            $site,
            $site->getNotFoundPage(),
            'n'
        );

        if (empty($page)) {
            throw new PageNotFoundException(
                'No default page defined for 404 not found error'
            );
        }

        $response = $this->getResponse();
        $response->setStatusCode(410);

        return $page;
    }

    /**
     * prepLayoutView
     *
     * @param Site $site
     * @param Page $page
     * @param      $requestedPageData
     * @param      $layoutOverRide
     *
     * @return void
     */
    protected function prepLayoutView(
        Site $site,
        Page $page,
        $requestedPageData,
        $layoutOverRide
    ) {
        /** @var ViewModel $layoutView */
        $layoutView = $this->layout();

        if (!empty($layoutOverRide)) {
            $layoutTemplatePath = $this->layoutManager->getSiteLayout(
                $this->currentSite,
                $layoutOverRide
            );

            $layoutView->setTemplate('layout/' . $layoutTemplatePath);
        }

        if ($this->pageInfo['currentRevisionId']
            != $this->pageInfo['revision']['revisionId']
        ) {
            $layoutView->setVariable('rcmDraft', true);
        }

        $layoutView->setVariable('page', $page);
        $layoutView->setVariable('site', $site);
        $layoutView->setVariable('requestedPageData', $requestedPageData);
    }

    /**
     * prepPageRevisionForDisplay
     *
     * @param Page $page
     * @param null $pageRevisionId
     *
     * @return void|Response
     */
    public function prepPageRevisionForDisplay(
        Page $page,
        $pageRevisionId = null
    ) {
        //  First Check for a page Revision
        if (!empty($pageRevisionId)) {
            $userCanSeeRevisions = $this->shouldShowRevisions(
                $this->currentSite->getSiteId(),
                $page->getName(),
                $page->getPageType()
            );

            if ($userCanSeeRevisions) {
                $revision = $page->getRevisionById($pageRevisionId);

                if (!empty($revision) || $revision instanceof Revision) {
                    $page->setCurrentRevision($revision);
                }

                return;

            } else {
                return $this->redirectToPage(
                    $page->getName(),
                    $page->getPageType()
                );
            }
        }

        // Check for staging
        if ($this->rcmIsSiteAdmin($this->currentSite)) {
            $revision = $page->getStagedRevision();

            if (!empty($revision) || $revision instanceof Revision) {
                $page->setCurrentRevision($revision);
                return;
            }
        }

        // Finally look for published revision
        $revision = $page->getPublishedRevision();
        if (!empty($revision) || $revision instanceof Revision) {
            $page->setCurrentRevision($revision);
        }

        return;
    }

    /**
     * getUnauthorizedResponse
     *
     * @return \Rcm\Http\Response
     */
    protected function getUnauthorizedResponse()
    {
        $response = new \Rcm\Http\Response();
        $response->setStatusCode(401);
        return $response;
    }
}
