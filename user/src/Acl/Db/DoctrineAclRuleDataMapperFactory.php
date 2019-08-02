<?php

namespace RcmUser\Acl\Db;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DoctrineAclRuleDataMapperFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DoctrineAclRuleDataMapperFactory
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

        $service = new DoctrineAclRuleDataMapper();
        $service->setEntityManager($em);
        $service->setEntityClass(
            \RcmUser\Acl\Entity\DoctrineAclRule::class
        );

        return $service;
    }
}
