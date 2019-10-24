<?php

namespace Rcm\SwitchUser\ApiController;

use RcmUser\Api\Authentication\GetIdentity;
use RcmUser\Api\GetPsrRequest;
use Reliv\RcmApiLib\Controller\AbstractRestfulJsonController;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class BaseApiController extends AbstractRestfulJsonController
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
}
