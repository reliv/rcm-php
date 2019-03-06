<?php

namespace RcmAdmin\Controller;

use \Zend\Http\Response;
use Rcm\Acl\ResourceName;
use Rcm\SiteSettingsSections\GetSectionDefinitions;
use RcmUser\Api\Acl\IsAllowed;
use RcmUser\Service\RcmUserService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ReactAdminHtmlRootController extends AbstractActionController
{
    /** @var RcmUserService */
    protected $rcmUserService;

    /** @var GetSectionDefinitions */
    protected $getSettingsSectionDefinition;

    public function __construct(
        RcmUserService $rcmUserService,
        GetSectionDefinitions $getSettingsSectionDefinitions
    ) {
        $this->rcmUserService = $rcmUserService;
        $this->getSettingsSectionDefinitions = $getSettingsSectionDefinitions;
    }

    /**
     * indexAction
     *
     * @return UnauthorizedResponse|ViewModel
     */
    public function indexAction()
    {
        if (!$this->rcmUserService->isAllowed(ResourceName::RESOURCE_SITES, 'admin')) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }

        $this->layout()->setTemplate('layout/blank');

        $view = new ViewModel();
        $view->setVariable(
            'siteSettingsSectionDefinitions',
            $this->getSettingsSectionDefinitions->__invoke()
        );

        return $view;
    }
}
