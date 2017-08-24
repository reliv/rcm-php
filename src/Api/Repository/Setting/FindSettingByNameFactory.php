<?php

namespace Rcm\Repository\Setting;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindSettingByNameFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindSettingByName
     */
    public function __invoke($serviceContainer)
    {
        return new FindSettingByName(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
