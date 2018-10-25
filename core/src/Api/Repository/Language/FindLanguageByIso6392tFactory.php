<?php

namespace Rcm\Api\Repository\Language;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindLanguageByIso6392tFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindLanguageByIso6392t
     */
    public function __invoke($serviceContainer)
    {
        return new FindLanguageByIso6392t(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
