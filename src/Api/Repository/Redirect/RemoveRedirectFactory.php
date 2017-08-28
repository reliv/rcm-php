<?php

namespace Rcm\Api\Repository\Redirect;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RemoveRedirectFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return RemoveRedirect
     */
    public function __invoke($serviceContainer)
    {
        return new RemoveRedirect(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
