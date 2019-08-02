<?php

namespace RcmUser\Ui\View\Helper\Factory;

use RcmUser\Ui\View\Helper\RcmUserIsAllowed;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * RcmUserHasRoleBasedAccess
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Ui\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUserHasRoleBasedAccess implements FactoryInterface
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $mgr mgr
     *
     * @return mixed|RcmUserIsAllowed
     */
    public function createService(ServiceLocatorInterface $mgr)
    {
        $serviceLocator = $mgr->getServiceLocator();
        $authorizeService = $serviceLocator->get(
            'RcmUser\Acl\Service\AuthorizeService'
        );
        $userAuthService = $serviceLocator->get(
            'RcmUser\Authentication\Service\UserAuthenticationService'
        );

        $service = new \RcmUser\Ui\View\Helper\RcmUserHasRoleBasedAccess(
            $authorizeService, $userAuthService
        );

        return $service;
    }
}
