<?php
/**
 * Admin Panel Controller for the CMS
 *
 * This file contains the Admin Panel Controller for the CMS.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */
namespace RcmAdmin\Controller;

use Rcm\Acl\CmsPermissionChecks;
use Rcm\Entity\Page;
use Rcm\Entity\Site;
use RcmUser\Acl\Service\AclDataService;
use RcmUser\Service\RcmUserService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Admin Panel Controller for the CMS
 *
 * This is Admin Panel Controller for the CMS.
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @method boolean rcmIsSiteAdmin(Site $site)  Is Site Administrator
 */
class AdminPanelController extends AbstractActionController
{
    /** @var array */
    protected $adminPanelConfig;

    /** @var \Rcm\Entity\Site */
    protected $currentSite;

    /** @var \Rcm\Acl\CmsPermissionChecks */
    protected $cmsPermissionChecks;

    /** @var AclDataService */
    protected $aclDataService;

    /**
     * Constructor
     *
     * @param array               $adminPanelConfig
     * @param Site                $currentSite
     * @param CmsPermissionChecks $cmsPermissionChecks
     */
    public function __construct(
        Array          $adminPanelConfig,
        Site $currentSite,
        CmsPermissionChecks $cmsPermissionChecks
    ) {
        $this->adminPanelConfig = $adminPanelConfig;
        $this->currentSite = $currentSite;
        $this->cmsPermissionChecks = $cmsPermissionChecks;
    }

    /**
     * Get the Admin Menu Bar
     *
     * @return mixed
     */
    public function getAdminWrapperAction()
    {
        $allowed = $this->cmsPermissionChecks->siteAdminCheck($this->currentSite);

        if (!$allowed) {
            return null;
        }

        /** @var RouteMatch $routeMatch */
        $routeMatch = $this->getEvent()->getRouteMatch();
        $siteId = $this->currentSite->getSiteId();
        $sourcePageName = $routeMatch->getParam('page', 'index');

        if ($sourcePageName instanceof Page) {
            $sourcePageName = $sourcePageName->getName();
        }

        $pageType = $routeMatch->getParam('pageType', 'n');

        $view = new ViewModel();
        if ($this->cmsPermissionChecks->isPageRestricted($siteId, $pageType, $sourcePageName, 'read') == true) {
            $view->setVariable('restrictions', true);
        }

        $view->setVariable('adminMenu', $this->adminPanelConfig);
        $view->setTemplate('rcm-admin/admin/admin');
        return $view;
    }
}
