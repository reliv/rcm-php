<?php

namespace Rcm\Api\Repository\Site;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Rcm\Api\Repository\Country\FindCountryByIso3;
use Rcm\Api\Repository\Domain\FindDomainByName;
use Rcm\Api\Repository\Language\FindLanguageByIso6392t;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CreateSiteFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return CreateSite
     */
    public function __invoke($serviceContainer)
    {
        return new CreateSite(
            $serviceContainer->get(EntityManager::class),
            $serviceContainer->get(FindDomainByName::class),
            $serviceContainer->get(FindCountryByIso3::class),
            $serviceContainer->get(FindLanguageByIso6392t::class)
        );
    }
}
