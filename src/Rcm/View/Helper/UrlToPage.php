<?php
/**
 * Rcm Url View Helper
 *
 * This file contains the class definition for the Rcm Url View Helper
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */
namespace Rcm\View\Helper;

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
    public function __invoke($pageName, $pageType = 'n', $pageRevision = null)
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
    public function url($pageName, $pageType = 'n', $pageRevision = null)
    {
        /** @var \Zend\Mvc\Controller\AbstractActionController $controller */
        $view = $this->getView();

        if ($pageType == 'n' && $pageName == 'index' && empty($pageRevision)) {
            return '/';
        } elseif ($pageType == 'n' && empty($pageRevision)) {
            return $view->url(
                'contentManager',
                array('page' => $pageName)
            );
        } elseif ($pageType == 'n' && !empty($pageRevision)) {
            return $view->url(
                'contentManager',
                array(
                    'revision' => $pageRevision,
                    'page' => $pageName,
                )
            );
        } elseif ($pageType != 'n' && !empty($pageRevision)) {
            return $view->url(
                'contentManagerWithPageType',
                array(
                    'revision' => $pageRevision,
                    'pageType' => $pageType,
                    'page' => $pageName,
                )
            );
        } else {
            return $view->url(
                'contentManagerWithPageType',
                array(
                    'pageType' => $pageType,
                    'page' => $pageName,
                )
            );
        }
    }
}
