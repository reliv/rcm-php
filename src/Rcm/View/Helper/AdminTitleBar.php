<?php
/**
 * Add Layout Container Helper.
 *
 * Contains the view helper to add a layout container to a page layout
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Common\View\Helper
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

namespace Rcm\View\Helper;

use \Zend\View\Helper\AbstractHelper;

/**
 * Create a layout container in your page layouts.
 *
 * This is a view helper to render out page containers inside page layouts.
 *
 * @category  Reliv
 * @package   Common\View\Helper
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 */
class AdminTitleBar extends AbstractHelper
{
    /**
     * Function called when using $this->view->adminTitleBar().  Will
     * call method renderAdminTitleBar.  See method renderLayoutContainer
     * for more info
     *
     * @param \Rcm\Entity\Page $page              page object
     * @param int              $displayedRevision id of displayed revision
     * @param string           $language          Language for links
     *
     * @return string Rendered HTML from plugins for the container specified
     */
    public function __invoke(\Rcm\Entity\Page $page, $displayedRevision, $language)
    {
        return $this->renderAdminTitleBar($page, $displayedRevision, $language);
    }

    /**
     * Get the rendered title bar for the CMS
     *
     * @param \Rcm\Entity\Page $page              Current Page Entity
     * @param int              $displayedRevision Displayed Revision ID Number
     * @param string           $language          Language for links
     *
     * @return null|string
     */
    public function renderAdminTitleBar(\Rcm\Entity\Page $page,
        $displayedRevision,
        $language
    ) {
        if (empty($displayedRevision) || !is_numeric($displayedRevision)) {
            return null;
        }

        $pagetype = $page->getPageType();

        /** @var \Rcm\Entity\PageRevision $currentRevision  */
        $currentRevision = $page->getRevisionById($displayedRevision);
        $currentRevisionId = $currentRevision->getPageRevId();
        $currentRevisionTitle = $currentRevision->getPageTitle();
        $currentRevisionTitle .= ' - Draft';

        $currentPublishedRev= $page->getCurrentRevision();

        if (!empty($currentPublishedRev)) {
            $currentPublishedRevId = $currentPublishedRev->getPageRevId();
            $currentPublishedRevTitle = $currentPublishedRev->getPageTitle();
            $currentPublishedRevTitle .= ' - Live';
        }

        $currentStagedRev = $page->getStagedRevision();

        if (!empty($currentStagedRev)) {
            $currentStagedRevId = $currentStagedRev->getPageRevId();
            $currentStagedRevTitle = $currentStagedRev->getPageTitle();
            $currentStagedRevTitle .= ' - Staged';
        }

        $lastSavedDraft = $page->getLastSavedRevision();

        if (!empty($lastSavedDraft)) {
            $lastSavedRevId = $lastSavedDraft->getPageRevId();
            $lastSavedRevTitle = $lastSavedDraft->getPageTitle();
            $lastSavedRevTitle .= ' - Latest Draft';
        }


        if (!empty($currentPublishedRevId)
            && !empty($currentPublishedRevTitle)
            && $currentPublishedRevId == $currentRevisionId
        ) {
            $currentRevisionTitle = $currentPublishedRevTitle;
        }

        if (!empty($currentStagedRevId)
            && !empty($currentStagedRevTitle)
            && $currentStagedRevId == $currentRevisionId
        ) {
            $currentRevisionTitle = $currentStagedRevTitle;
        }

        $html = '<div id="rcmTitleBar">';
        $html .= '<ul id="rcmAdminTitleBarMenu">';
        $html .= "<li ";

        if (!empty($currentPublishedRevId) || !empty($currentStagedRevId)) {
            $html .= 'class="rcmTitleBarHasAdditional" >'.$currentRevisionTitle;
            $html .= '<ul>';

            if (!empty($currentPublishedRevId)) {
                $html .= '<li><a href="';
                $html .= $this->getRevisionLink(
                    $page,
                    $pagetype,
                    $currentPublishedRev,
                    $language
                );
                $html .= '" >Published Revision</a></li>';
            }

            if (!empty($currentStagedRevId)) {
                $html .= '<li><a href="';
                $html .= $this->getRevisionLink(
                    $page,
                    $pagetype,
                    $currentStagedRev,
                    $language
                );
                $html .= '" >Staged Revision</a></li>';
            }

            if (!empty($lastSavedRevId)) {
                $html .= '<li><a href="';
                $html .= $this->getRevisionLink(
                    $page,
                    $pagetype,
                    $lastSavedDraft,
                    $language
                );
                $html .= '" >Last Saved Draft - '.$lastSavedDraft->getAuthor();
                $html .= '</a></li>';
            }

            $html .= '</ul>';

        } else {
            $html .= '>'.$currentRevisionTitle;
        }


        $html .= "</li>";
        $html .= '</ul>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Get a revision link for title bar
     *
     * @param \Rcm\Entity\Page         $page     RCM Page Entity
     * @param string                   $pageType Type of page
     * @param \Rcm\Entity\PageRevision $revision RCM Page Revision Entity
     * @param string                   $language Language to use for link
     *
     * @return mixed
     */
    protected function getRevisionLink(\Rcm\Entity\Page $page,
        $pageType,
        \Rcm\Entity\PageRevision $revision,
        $language
    ) {
        $renderer = $this->getView();

        if ($pageType != 'n') {
            $revisionUrl =  $renderer->url(
                'contentManagerWithPageType',
                array(
                    'page' => $page->getName(),
                    'pageType' => $pageType,
                    'language' => $language,
                    'revision' => $revision->getPageRevId()
                )
            );
        } else {
            $revisionUrl =  $renderer->url(
                'contentManager',
                array(
                    'page' => $page->getName(),
                    'language' => $language,
                    'revision' => $revision->getPageRevId()
                )
            );
        }

        return $revisionUrl;
    }
}