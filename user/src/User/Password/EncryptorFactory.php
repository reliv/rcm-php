<?php

namespace RcmUser\User\Password;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class EncryptorFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class EncryptorFactory
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
        $cfg = $serviceLocator->get(
            \RcmUser\User\Config::class
        );
        $encryptor = new Bcrypt();
        $encryptor->setCost(
            $cfg->get(
                'Encryptor.passwordCost',
                14
            )
        );

        return $encryptor;
    }
}
