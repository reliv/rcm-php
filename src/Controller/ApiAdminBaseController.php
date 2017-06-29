<?php

namespace RcmAdmin\Controller;

use Rcm\Controller\AbstractRestfulJsonController;
use Rcm\Tracking\Exception\TrackingException;

/**
 * Class ApiAdminBaseController
 *
 * ApiAdminBaseController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAdmin\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
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
     * @return \Doctrine\ORM\EntityManagerInterface
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
     * @return \RcmUser\User\Entity\User
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
     * @return \RcmUser\User\Entity\User
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
