<?php

namespace RcmAdmin\Controller;

use Rcm\Controller\AbstractRestfulJsonController;
use Rcm\Tracking\Exception\TrackingException;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ApiAdminBaseController extends AbstractRestfulJsonController
{
    /**
     * getConfig
     *
     * @return array
     */
    protected function getConfig()
    {
        return $this->serviceLocator->get('config');
    }

    /**
     * getEntityManager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->serviceLocator->get('Doctrine\ORM\EntityManager');
    }

    /**
     * getCurrentSite
     *
     * @return \Rcm\Entity\Site
     */
    protected function getCurrentSite()
    {
        return $this->serviceLocator->get(\Rcm\Service\CurrentSite::class);
    }

    /**
     * getCurrentUser
     *
     * @return \RcmUser\User\Entity\UserInterface
     */
    protected function getCurrentUser()
    {
        /** @var \RcmUser\Service\RcmUserService $rcmUserService */
        $rcmUserService = $this->serviceLocator->get(
            \RcmUser\Service\RcmUserService::class
        );

        return $rcmUserService->getCurrentUser();
    }

    /**
     * @return \RcmUser\User\Entity\UserInterface
     * @throws TrackingException
     */
    protected function getCurrentUserTracking()
    {
        $user = $this->getCurrentUser();

        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . get_class($this));
        }

        return $user;
    }
}
