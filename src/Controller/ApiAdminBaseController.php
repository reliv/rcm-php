<?php


namespace RcmAdmin\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

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

class ApiAdminBaseController extends AbstractRestfulController
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
        return $this->serviceLocator->get('Rcm\Service\CurrentSite');
    }

    /**
     * getCurrentUser
     *
     * @return \RcmUser\User\Entity\User
     */
    protected function getCurrentUser()
    {
        return $this->rcmUserGetCurrentUser();
    }

    /**
     * getCurrentAuthor
     *
     * @param string $default
     *
     * @return string
     */
    protected function getCurrentAuthor($default = 'Unknown Author')
    {
        $user = $this->getCurrentUser();

        // @todo How should we handle this case?
        if (empty($user)) {
            return $default;
        }

        return $user->getName();
    }
}
