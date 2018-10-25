<?php

namespace Rcm\Api\Repository\Redirect;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CreateRedirectFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return CreateRedirect
     */
    public function __invoke($serviceContainer)
    {
        return new CreateRedirect(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
