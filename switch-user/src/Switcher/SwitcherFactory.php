<?php

namespace Rcm\SwitchUser\Switcher;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class SwitcherFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $container
     *
     * @return Switcher
     */
    public function __invoke($container)
    {
        $config = $container->get('config');

        $switcherMethod = $config['Rcm\\SwitchUser']['switcherMethod'];
        $switcherServiceName = $config['Rcm\\SwitchUser']['switcherServices'][$switcherMethod];
        $switcher = $container->get($switcherServiceName);

        return $switcher;
    }
}
