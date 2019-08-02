<?php

namespace RcmUser\Log;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ConfigFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ConfigFactory
{
    /**
     * createService
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator serviceLocator
     *
     * @return Config
     */
    public function __invoke($serviceLocator)
    {
        /** @var \RcmUser\Config\Config $config */
        $config = $serviceLocator->get(\RcmUser\Config\Config::class);

        return new Config(
            $config->get('Log\Config', [])
        );
    }
}
