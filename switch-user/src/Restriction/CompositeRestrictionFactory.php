<?php

namespace Rcm\SwitchUser\Restriction;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CompositeRestrictionFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $container
     *
     * @return CompositeRestriction
     */
    public function __invoke($container)
    {
        $config = $container->get('config');

        return new CompositeRestriction(
            $config,
            $container
        );
    }
}
