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
use Rcm\Service\LayoutManager;
use Rcm\Service\PageManager;
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
 * @method boolean rcmUserIsAllowed($resource, $action, $providerId) Is User Allowed
 */
class IndexController extends AbstractActionController
{
    /** @var string */
    public $pageName;

    /** @var string */
    public $pageType;

    /** @var integer */
    public $pageRevisionId;

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
     * @param PageManager   $pageManager   Page Manager needed to get current page.
     * @param LayoutManager $layoutManager Layout Manager to handle themes
     * @param integer       $siteId        Current Site Id
     */
    public function __construct(
        PageManager $pageManager,
        LayoutManager $layoutManager,
        $siteId
    ) {
        $this->pageManager = $pageManager;
        $this->layoutManager = $layoutManager;
        $this->siteId = $siteId;
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


        $userCanSeeRevisions = $this->shouldShowRevisions();

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

        /** @var ViewModel $layoutView */
        $layoutView = $this->layout();

        if (!empty($pageInfo['siteLayoutOverride'])) {
            $layoutTemplatePath = $this->layoutManager->getSiteLayout(
                $pageInfo['siteLayoutOverride']
            );

            $layoutView->setTemplate('layout/' . $layoutTemplatePath);
        }

        if ($pageInfo['currentRevisionId'] != $pageInfo['revision']['revisionId']) {
            $layoutView->setVariable('rcmDraft', true);
        }

        $layoutView->setVariable('pageInfo', $pageInfo);
        $layoutView->setVariable('shortRevList', $this->getShortRevisionList());

        $viewModel = new ViewModel(array('pageInfo' => $pageInfo));

        $viewModel->setTemplate(
            'pages/'
            . $this->layoutManager->getSitePageTemplate($pageInfo['pageLayout'])
        );

        return $viewModel;
    }


    /**
     * Check to make sure user can see revisions
     *
     * @return bool
     */
    protected function shouldShowRevisions()
    {
        $allowedRevisions = $this->rcmUserIsAllowed(
            'sites.' . $this->siteId . '.pages.' . $this->pageName,
            'edit',
            'Rcm\Acl\ResourceProvider'
        );

        if ($allowedRevisions) {
            return true;
        }

        $allowedRevisions = $this->rcmUserIsAllowed(
            'sites.' . $this->siteId . '.pages.' . $this->pageName,
            'approve',
            'Rcm\Acl\ResourceProvider'
        );

        if ($allowedRevisions) {
            return true;
        }

        $allowedRevisions = $this->rcmUserIsAllowed(
            'sites.' . $this->siteId . '.pages.' . $this->pageName,
            'revisions',
            'Rcm\Acl\ResourceProvider'
        );

        if ($allowedRevisions) {
            return true;
        }

        $allowedRevisions = $this->rcmUserIsAllowed(
            'sites.' . $this->siteId . '.pages',
            'create',
            'Rcm\Acl\ResourceProvider'
        );

        if ($allowedRevisions) {
            return true;
        }

        return false;
    }

    public function getShortRevisionList()
    {
        $allowed = $this->rcmUserIsAllowed(
            'sites.' . $this->siteId,
            'admin',
            'Rcm\Acl\ResourceProvider'
        );

        if (!$allowed) {
            return array();
        }

        $page = $this->pageManager->getPageByName($this->pageName, $this->pageType);

        if (empty($page)) {
            return array();
        }

        $revisions = array(
            'Live' => $page->getCurrentRevision(),
            'Staged' => $page->getStagedRevision(),
            'Draft' => $page->getLastSavedDraftRevision(),
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
                    $revision->getRevisionId()
                ),

                'author' => $revision->getAuthor(),
                'date' => $revision->getCreatedDate(),
                'selected' => false,
            );

            if ($this->pageRevisionId == $revision->getRevisionId()) {
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
