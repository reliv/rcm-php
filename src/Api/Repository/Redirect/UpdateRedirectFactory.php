<?php

namespace Rcm\Repository\Redirect;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class UpdateRedirectFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return UpdateRedirect
     */
    public function __invoke($serviceContainer)
    {
        return new UpdateRedirect(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
