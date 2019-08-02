<?php

namespace RcmUser\User\Data;

use Interop\Container\ContainerInterface;
use RcmUser\User\InputFilter\UserInputFilter;
use Zend\InputFilter\Factory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserValidatorFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UserValidatorFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return UserValidator
     */
    public function __invoke($serviceLocator)
    {
        $config = $serviceLocator->get(
            \RcmUser\User\Config::class
        )->get(
            'InputFilter',
            []
        );
        $userInputFilter = new UserInputFilter();
        $factory = new Factory();

        $service
            = new UserValidator($factory, $userInputFilter, $config);

        return $service;
    }
}
