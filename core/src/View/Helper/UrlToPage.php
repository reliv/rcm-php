<?php

namespace Rcm\View\Helper;

use Rcm\Page\PageTypes\PageTypes;
use Zend\View\Helper\AbstractHelper;

/**
 * Rcm Url View Helper
 *
 * Rcm Url View Helper.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class UrlToPage extends AbstractHelper
{
    /**
     * Redirect to a page
     *
     * @param string $pageName     Page Name
     * @param string $pageType     Page Type
     * @param string $pageRevision Page Revision
     *
     * @return \Zend\Http\Response
     */
    public function __invoke($pageName, $pageType = PageTypes::NORMAL, $pageRevision = null)
    {
        return $this->url($pageName, $pageType, $pageRevision);
    }

    /**
     * Redirect to same page with no version numbers
     *
     * @param string       $pageName Page Name
     * @param string       $pageType Page Type
     * @param integer|null $pageRevision  Revision for link
     *
     * @return string
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function url($pageName, $pageType = PageTypes::NORMAL, $pageRevision = null)
    {
        /** @var \Zend\Mvc\Controller\AbstractActionController $controller */
        $view = $this->getView();

        if ($pageType == PageTypes::NORMAL && $pageName == 'index' && empty($pageRevision)) {
            return '/';
        } elseif ($pageType == PageTypes::NORMAL && empty($pageRevision)) {
            return $view->url(
                'contentManager',
                ['page' => $pageName]
            );
        } elseif ($pageType == PageTypes::NORMAL && !empty($pageRevision)) {
            return $view->url(
                'contentManager',
                [
                    'revision' => $pageRevision,
                    'page' => $pageName,
                ]
            );
        } elseif ($pageType != PageTypes::NORMAL && !empty($pageRevision)) {
            return $view->url(
                'contentManagerWithPageType',
                [
                    'revision' => $pageRevision,
                    'pageType' => $pageType,
                    'page' => $pageName,
                ]
            );
        } else {
            return $view->url(
                'contentManagerWithPageType',
                [
                    'pageType' => $pageType,
                    'page' => $pageName,
                ]
            );
        }
    }
}
