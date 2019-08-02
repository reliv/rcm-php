<?php

namespace RcmUser\Acl\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AclDataServiceFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AclDataServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return AclDataService
     */
    public function __invoke($serviceLocator)
    {
        $aclRoleDataMapper = $serviceLocator->get(
            \RcmUser\Acl\Db\AclRoleDataMapper::class
        );

        $aclRuleDataMapper = $serviceLocator->get(
            \RcmUser\Acl\Db\AclRuleDataMapper::class
        );

        $userEventManager = $serviceLocator->get(
            \RcmUser\Event\UserEventManager::class
        );

        $service = new AclDataService(
            $aclRoleDataMapper,
            $aclRuleDataMapper,
            $userEventManager
        );

        return $service;
    }
}
