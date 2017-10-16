<?php

namespace Rcm\Api\Repository\Country;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindCountriesFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindCountries
     */
    public function __invoke($serviceContainer)
    {
        return new FindCountries(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
