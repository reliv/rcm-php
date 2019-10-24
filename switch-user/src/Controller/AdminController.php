<?php

namespace Rcm\SwitchUser\Controller;

use RcmUser\Api\Authentication\GetIdentity;
use RcmUser\Api\GetPsrRequest;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AdminController extends AbstractActionController
{
    /**
     * @param null $default
     *
     * @return null|\RcmUser\User\Entity\UserInterface
     */
    protected function getCurrentUser($default = null)
    {
        /** @var GetIdentity $getIdentity */
        $getIdentity = $this->getServiceLocator()->get(
            GetIdentity::class
        );

        $psrRequest = GetPsrRequest::invoke();

        return $getIdentity->__invoke(
            $psrRequest,
            $default
        );
    }

    /**
     * getSwitchUserService
     *
     * @return \Rcm\SwitchUser\Service\SwitchUserService
     */
    protected function getSwitchUserService()
    {
        return $this->getServiceLocator()->get(
            \Rcm\SwitchUser\Service\SwitchUserService::class
        );
    }

    /**
     * getSwitchUserAclService
     *
     * @return \Rcm\SwitchUser\Service\SwitchUserAclService
     */
    protected function getSwitchUserAclService()
    {
        return $this->getServiceLocator()->get(
            \Rcm\SwitchUser\Service\SwitchUserAclService::class
        );
    }

    /**
     * isAllowed
     *
     * @param $suUser
     *
     * @return bool|mixed
     */
    protected function isAllowed($suUser)
    {
        return $this->getSwitchUserAclService()->isSuAllowed($suUser);
    }

    /**
     * indexAction
     *
     * @return \Zend\Stdlib\ResponseInterface|ViewModel
     */
    public function indexAction()
    {
        $switchUserService = $this->getSwitchUserService();

        $view = new ViewModel();

        $adminUser = $switchUserService->getCurrentImpersonatorUser();
        $targetUser = $this->getCurrentUser();
        $view->setVariable(
            'targetUser',
            $targetUser
        );
        $view->setVariable(
            'switchBackMethod',
            $switchUserService->getSwitchBackMethod()
        );

        if (empty($adminUser)) {
            $view->setVariable('targetUser', null);
            $adminUser = $targetUser;
        }

        if (!$this->isAllowed($adminUser)) {
            $this->getResponse()->setStatusCode(401);

            return $this->getResponse();
        }

        $view->setVariable('adminUser', $adminUser);

        return $view;
    }
}
