<?php

namespace RcmUser\User\Data;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserDataPreparerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UserDataPreparerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return UserDataPreparer
     */
    public function __invoke($serviceLocator)
    {
        $service = new UserDataPreparer();

        return $service;
    }
}
