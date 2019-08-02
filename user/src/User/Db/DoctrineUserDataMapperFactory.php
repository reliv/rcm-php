<?php

namespace RcmUser\User\Db;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DoctrineUserDataMapperFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DoctrineUserDataMapperFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return DoctrineUserDataMapper
     */
    public function __invoke($serviceLocator)
    {
        $em = $serviceLocator->get(
            \Doctrine\ORM\EntityManager::class
        );
        $udp = $serviceLocator->get(
            \RcmUser\User\Data\UserDataPreparer::class
        );
        $udv = $serviceLocator->get(
            \RcmUser\User\Data\UserValidator::class
        );

        $service = new DoctrineUserDataMapper();
        $service->setEntityManager($em);
        $service->setEntityClass(
            \RcmUser\User\Entity\DoctrineUserInterface::class
        );
        $service->setUserDataPreparer($udp);
        $service->setUserValidator($udv);

        return $service;
    }
}
