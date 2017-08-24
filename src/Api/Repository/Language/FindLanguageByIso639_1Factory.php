<?php

namespace Rcm\Repository\Language;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindLanguageByIso639_1Factory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindLanguageByIso639_1
     */
    public function __invoke($serviceContainer)
    {
        return new FindLanguageByIso639_1(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
