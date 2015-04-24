<?php
/**
 * Redirect To Page Controller Plugin
 *
 * This file contains the Redirect To Page Controller Plugin.
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

use Rcm\Acl\CmsPermissionChecks;
use Rcm\Entity\Site;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Redirect To Page Controller Plugin
 *
 * Redirect To Page Controller Plugin.  This plugin is used to redirect a user
 * to a CMS page by sending the URL to the page and the page type of that page.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class IsSiteAdmin extends AbstractPlugin
{
    /** @var \Rcm\Acl\CmsPermissionChecks  */
    public $checker;

    public function __construct(CmsPermissionChecks $cmsPermissionChecks)
    {
        $this->checker = $cmsPermissionChecks;
    }

    /**
     * isAdmin
     *
     * @param Site $site Site to check
     *
     * @return \Zend\Http\Response
     */
    public function __invoke(Site $site)
    {
        return $this->checker->siteAdminCheck($site);
    }
}
