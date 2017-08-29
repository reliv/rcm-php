<?php

namespace Rcm\Api\Repository\Language;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindLanguageByIso6391Factory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindLanguageByIso6391
     */
    public function __invoke($serviceContainer)
    {
        return new FindLanguageByIso6391(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
