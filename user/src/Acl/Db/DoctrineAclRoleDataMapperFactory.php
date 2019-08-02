<?php

namespace RcmUser\Acl\Db;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DoctrineAclRoleDataMapperFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DoctrineAclRoleDataMapperFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return DoctrineAclRoleDataMapper
     */
    public function __invoke($serviceLocator)
    {
        $em = $serviceLocator->get(
            \Doctrine\ORM\EntityManager::class
        );
        $config = $serviceLocator->get(
            \RcmUser\Acl\Config::class
        );

        $service = new DoctrineAclRoleDataMapper($config);
        $service->setEntityManager($em);
        $service->setEntityClass(
            \RcmUser\Acl\Entity\DoctrineAclRole::class
        );

        return $service;
    }
}
