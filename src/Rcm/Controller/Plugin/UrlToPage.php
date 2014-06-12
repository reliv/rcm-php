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
     * @param string $pageName Page Name
     * @param string $pageType Page Type
     *
     * @return \Zend\Http\Response
     */
    public function __invoke($pageName, $pageType='n')
    {
        return $this->url($pageName, $pageType);
    }

    /**
     * Redirect to same page with no version numbers
     *
     * @param string $pageName Page Name
     * @param string $pageType Page Type
     *
     * @return \Zend\Http\Response
     */
    public  function url($pageName, $pageType)
    {
        /** @var \Zend\Mvc\Controller\AbstractActionController $controller */
        $controller = $this->getController();

        if ($pageType == 'n' && $pageName == 'index') {
            return '/';
        } elseif ($pageType == 'n') {
            return $controller->url()->fromRoute(
                'contentManager',
                array('page' => $pageName)
            );
        } else {
            return $controller->url()->fromRoute(
                'contentManagerWithPageType',
                array(
                    'pageType' => $pageType,
                    'page' => $pageName,
                )
            );
        }
    }
}