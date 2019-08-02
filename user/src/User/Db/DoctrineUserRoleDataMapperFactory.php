<?php

namespace RcmUser\User\Db;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DoctrineUserRoleDataMapperFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DoctrineUserRoleDataMapperFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return object
     */
    public function __invoke($serviceLocator)
    {
        $em = $serviceLocator->get(
            \Doctrine\ORM\EntityManager::class
        );
        $acldm = $serviceLocator->get(
            \RcmUser\Acl\Db\AclRoleDataMapper::class
        );
        $service = new DoctrineUserRoleDataMapper($acldm);
        $service->setEntityManager($em);
        $service->setEntityClass(
            \RcmUser\User\Entity\DoctrineUserRole::class
        );

        return $service;
    }
}
