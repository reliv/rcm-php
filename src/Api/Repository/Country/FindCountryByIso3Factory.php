<?php

namespace Rcm\Repository\Country;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindCountryByIso3Factory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindCountryByIso3
     */
    public function __invoke($serviceContainer)
    {
        return new FindCountryByIso3(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
