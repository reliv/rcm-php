<?php

namespace Rcm\Repository\Redirect;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindRedirectsFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindRedirects
     */
    public function __invoke($serviceContainer)
    {
        return new FindRedirects(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
