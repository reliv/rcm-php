<?php

namespace RcmAdmin\Controller;

use Rcm\Acl\AclActions;
use Rcm\Acl\NotAllowedException;
use Rcm\Acl\ResourceName;
use Rcm\Http\NotAllowedResponseJsonZf2;
use Rcm\RequestContext\RequestContext;
use Rcm\SecureRepo\PageSecureRepo;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $this->getServiceLocator()->get(
            \Rcm\Service\CurrentSite::class
        );

        $pageSecureRepo = $this->getServiceLocator()->get(RequestContext::class)->get(PageSecureRepo::class);
        try {
            $pageSecureRepo->assertIsAllowed(
                AclActions::READ,
                ['siteId' => $currentSite->getSiteId()]
            );
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
        }

        $view = new ViewModel();
        //fixes rendering site's header and footer in the dialog
        $view->setTerminal(true);

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

        /** @var ResourceName $resourceName */
        $resourceName = $this->getServiceLocator()->get(
            ResourceName::class
        );

        $resourceId = $resourceName->get(
            ResourceName::RESOURCE_SITES,
            $currentSiteId,
            ResourceName::RESOURCE_PAGES,
            $pageType,
            $sourcePageName
        );

        /** @var \RcmUser\Acl\Service\AclDataService $aclDataService */
        $aclDataService = $this->getServiceLocator()->get(
            \RcmUser\Acl\Service\AclDataService::class
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
