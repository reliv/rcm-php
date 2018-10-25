<?php

namespace Rcm\Api\Repository\Country;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindOneCountryFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindOneCountry
     */
    public function __invoke($serviceContainer)
    {
        return new FindOneCountry(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
