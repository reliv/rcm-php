<?php
/**
 * URL To Page Controller Plugin
 *
 * This file contains the URL To Page Controller Plugin
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
namespace Rcm\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * URL To Page Controller Plugin
 *
 * URL To Page Controller Plugin.  This plugin is used to get the real URL to a
 * page for the CMS by passing it the page name and page type for the requested
 * url.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class UrlToPage extends AbstractPlugin
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
        $controller = $this->getController();

        if ($pageType == 'n' && $pageName == 'index' && empty($pageRevision)) {
            return '/';
        } elseif ($pageType == 'n' && empty($pageRevision)) {
            return $controller->url()->fromRoute(
                'contentManager',
                ['page' => $pageName]
            );
        } elseif ($pageType == 'n' && !empty($pageRevision)) {
            return $controller->url()->fromRoute(
                'contentManager',
                [
                    'revision' => $pageRevision,
                    'page' => $pageName,
                ]
            );
        } elseif ($pageType != 'n' && !empty($pageRevision)) {
            return $controller->url()->fromRoute(
                'contentManagerWithPageType',
                [
                    'revision' => $pageRevision,
                    'pageType' => $pageType,
                    'page' => $pageName,
                ]
            );
        } else {
            return $controller->url()->fromRoute(
                'contentManagerWithPageType',
                [
                    'pageType' => $pageType,
                    'page' => $pageName,
                ]
            );
        }
    }
}
