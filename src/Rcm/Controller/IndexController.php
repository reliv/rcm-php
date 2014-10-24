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

use Rcm\Exception\ContainerNotFoundException;
use Rcm\Entity\Site;
use Rcm\Service\LayoutManager;
use Rcm\Service\SiteManager;
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
 */
class IndexController extends AbstractActionController
{
    /** @var string */
    public $pageName;

    /** @var string */
    public $pageType;

    /** @var integer */
    public $pageRevisionId;

    /** @var \Rcm\Service\SiteManager */
    protected $siteManager;

    /** @var \Rcm\Entity\Site  */
    protected $currentSite;

    /** @var integer */
    protected $siteId;

    /** @var \Rcm\Service\PageManager */
    protected $pageManager;

    /** @var \Rcm\Service\LayoutManager */
    protected $layoutManager;

    protected $pageInfo;
    protected $notFound = false;

    /**
     * Constructor
     *
     * @param SiteManager   $siteManager     Site Manager needed to get current page.
     * @param LayoutManager $layoutManager   Layout Manager to get layouts.
     * @param Site          $currentSite     Current Site Entity
     */
    public function __construct(
        SiteManager   $siteManager,
        LayoutManager $layoutManager,
        Site          $currentSite
    ) {
        $this->siteManager = $siteManager;
        $this->pageManager = $siteManager->getPageManager();
        $this->layoutManager = $layoutManager;
        $this->siteId = $siteManager->getCurrentSiteId();
        $this->currentSite = $currentSite;
    }

    /**
     * Look for page titled not-found.  If can't find a CMS page by that name
     * throw a generic 404 and let Zend take care of the error handling.
     *
     * @return mixed|null
     */
    protected function pageNotFound()
    {
        $this->notFound = true;

        try {
            $this->pageName = 'not-found';
            $this->pageType = 'n';
            $this->pageRevisionId = null;

            $pageInfo = $this->pageManager->getRevisionInfo('not-found');

            /** @var \Zend\Http\Response $response */
            $response = $this->getResponse();
            $response->setStatusCode(404);

            return $pageInfo;
        } catch (ContainerNotFoundException $e) {
            return $this->notFoundAction();
        }
    }

    /**
     * Index Action.  Main action for page in the CMS.
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $this->pageName = $this->getEvent()
            ->getRouteMatch()
            ->getParam('page', 'index');

        $this->pageType = $this->getEvent()
            ->getRouteMatch()
            ->getParam('pageType', 'n');

        $this->pageRevisionId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('revision', null);


        $userCanSeeRevisions = $this->shouldShowRevisions(
            $this->siteManager->getCurrentSiteId(),
            $this->pageName,
            $this->pageType
        );

        if (!$userCanSeeRevisions && $this->pageRevisionId) {
            return $this->redirectToPage($this->pageName, $this->pageType);
        }

        try {
            $pageInfo = $this->pageManager->getRevisionInfo(
                $this->pageName,
                $this->pageRevisionId,
                $this->pageType,
                $userCanSeeRevisions
            );
        } catch (ContainerNotFoundException $e) {
            $pageInfo = $this->pageNotFound();

            if ($pageInfo instanceof ViewModel) {
                return $pageInfo;
            }
        }

        $this->pageInfo = $pageInfo;

        $allowed = $this->checkPermissions();

        if (!$allowed) {
            return $this->getUnauthorizedResponse();
        }

        $this->prepLayoutView();

        $viewModel = new ViewModel(array('pageInfo' => $pageInfo));

        $viewModel->setTemplate(
            'pages/'
            . $this->layoutManager->getSitePageTemplate($this->currentSite, $pageInfo['pageLayout'])
        );

        return $viewModel;
    }

    protected function getUnauthorizedResponse()
    {
        $response = new \Rcm\Http\Response();
        $response->setStatusCode(401);
        return $response;
    }

    protected function checkPermissions()
    {
        $allowed = $this->rcmIsAllowed(
            'sites.' . $this->siteId . '.pages.' . $this->pageInfo['pageType'] . '.' . $this->pageInfo['name'],
            'read'
        );

        $url = $this->request->getUriString();
        $parsedLogin = parse_url($url);
        $siteLoginPage = $this->siteManager->getSiteLoginPage();
        $notAuthorizedPage = $this->siteManager->getSiteNotAuthorizedPage();

        if ($siteLoginPage == $url
            || $siteLoginPage == $parsedLogin['path']
            || $notAuthorizedPage == $url
            || $notAuthorizedPage == $parsedLogin['path']
        ) {
            $allowed = true;
        }

        return $allowed;
    }

    protected function prepLayoutView()
    {
        /** @var ViewModel $layoutView */
        $layoutView = $this->layout();

        if (!empty($this->pageInfo['siteLayoutOverride'])) {
            $layoutTemplatePath = $this->layoutManager->getSiteLayout(
                $this->currentSite,
                $this->pageInfo['siteLayoutOverride']
            );

            $layoutView->setTemplate('layout/' . $layoutTemplatePath);
        }

        if ($this->pageInfo['currentRevisionId'] != $this->pageInfo['revision']['revisionId']) {
            $layoutView->setVariable('rcmDraft', true);
        }

        $layoutView->setVariable('pageInfo', $this->pageInfo);
        $layoutView->setVariable('shortRevList', $this->getShortRevisionList());
    }

    public function getShortRevisionList()
    {
        $allowed = $this->rcmIsSiteAdmin($this->currentSite);

        if (!$allowed) {
            return array();
        }

        $page = $this->pageManager->getPageRevisionList($this->pageName, $this->pageType);

        if (empty($page)) {
            return array();
        }

        $revisions = array(
            'Live' => $page['publishedRevision'],
            'Staged' => $page['stagedRevision'],
            'Draft' => $page['lastDraft'],
        );

        $return = array();
        $selected = 'Draft';

        /** @var \Rcm\Entity\Revision $revision */
        foreach ($revisions as $key => $revision) {
            if (empty($revision)) {
                continue;
            }

            $return[$key] = array(
                'href' => $this->urlToPage(
                    $this->pageName,
                    $this->pageType,
                    $revision['revisionId']
                ),

                'author' => $revision['author'],
                'date' => $revision['createdDate'],
                'selected' => false,
            );

            if (($this->pageInfo['revision']['revisionId'] == $revision['revisionId'])
                || (empty($this->pageRevisionId) && $key == 'Live')
            ) {
                $return[$key]['selected'] = true;
                $selected = $key;
            }

            if ($key == 'Live') {
                $return[$key]['href'] = $this->urlToPage(
                    $this->pageName,
                    $this->pageType
                );
            }
        }

        $return['current'] = $selected;
        return $return;
    }
}
