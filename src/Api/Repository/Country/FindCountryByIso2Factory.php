<?php

namespace Rcm\Repository\Country;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindCountryByIso2Factory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindCountryByIso2
     */
    public function __invoke($serviceContainer)
    {
        return new FindCountryByIso2(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
