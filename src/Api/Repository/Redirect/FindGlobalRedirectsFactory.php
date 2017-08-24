<?php

namespace Rcm\Repository\Redirect;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindGlobalRedirectsFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindGlobalRedirects
     */
    public function __invoke($serviceContainer)
    {
        return new FindGlobalRedirects(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
