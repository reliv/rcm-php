<?php

namespace RcmAdmin\Controller;

use \Zend\Http\Response;
use Rcm\Acl\ResourceName;
use RcmUser\Api\Acl\IsAllowed;
use RcmUser\Service\RcmUserService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ReactAdminHtmlRootController extends AbstractActionController
{
    protected $rcmUserService;

    public function __construct(RcmUserService $rcmUserService)
    {
        $this->rcmUserService = $rcmUserService;
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

        return new ViewModel();
    }
}
