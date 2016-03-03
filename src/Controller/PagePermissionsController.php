<?php

namespace RcmAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * PagePermissions
 *
 * Page Permissions controller
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAdmin\Controller
 * @author    Inna Davis <idavis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class PagePermissionsController extends AbstractActionController
{
    /**
     * @var \RcmUser\Acl\Service\AclDataService $aclDataService
     */
    protected $aclDataService;

    /**
     * Getting all Roles list and rules if role has one
     *
     * @return ViewModel
     */
    public function pagePermissionsAction()
    {

        $view = new ViewModel();
        //fixes rendering site's header and footer in the dialog
        $view->setTerminal(true);

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $this->getServiceLocator()->get(
            'Rcm\Service\CurrentSite'
        );

        $currentSiteId = $currentSite->getSiteId();

        $sourcePageName = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageName',
                'index'
            );

        $pageType = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageType',
                'n'
            );

        $resourceId = 'sites.' . $currentSiteId . '.pages.' . $pageType . '.'
            . $sourcePageName;
        /** @var \RcmUser\Acl\Service\AclDataService $aclDataService */
        $aclDataService = $this->getServiceLocator()->get(
            'RcmUser\Acl\AclDataService'
        );

        //getting all set rules by resource Id
        $rules = $aclDataService->getRulesByResource($resourceId)->getData();

        //getting list of all dynamically created roles
        $allRoles = $aclDataService->getNamespacedRoles()->getData();

        $rolesHasRules = [];

        foreach ($rules as $setRuleFor) {
            //getting only the ones that are allow
            if ($setRuleFor->getRule() == 'allow') {
                $rolesHasRules[] = $setRuleFor->getRoleId();
            }
        }

        $selectedRoles = [];

        foreach ($allRoles as $key => $role) {
            $roleId = $role->getRoleId();
            if (in_array($roleId, $rolesHasRules)) {
                $selectedRoles[$roleId] = $role;
            }
        }

        $data = [
            'siteId' => $currentSiteId,
            'pageType' => $pageType,
            'pageName' => $sourcePageName,
            'roles' => $allRoles,
            'selectedRoles' => $selectedRoles,
        ];

        $view->setVariable('data', $data);

        $view->setVariable(
            'rcmPageName',
            $sourcePageName
        );
        $view->setVariable(
            'rcmPageType',
            $pageType
        );

        return $view;

    }
}
