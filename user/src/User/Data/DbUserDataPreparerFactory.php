<?php

namespace RcmUser\User\Data;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DbUserDataPreparerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DbUserDataPreparerFactory
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

        $encrypt = $serviceLocator->get(
            \RcmUser\User\Password\Password::class
        );
        $service = new DbUserDataPreparer();
        $service->setEncryptor($encrypt);

        return $service;
    }
}
