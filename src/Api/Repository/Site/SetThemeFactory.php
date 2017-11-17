<?php

namespace Rcm\Api\Repository\Site;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class SetThemeFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return SetTheme
     */
    public function __invoke($serviceContainer)
    {
        return new SetTheme(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
