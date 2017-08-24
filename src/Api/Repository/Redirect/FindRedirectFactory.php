<?php

namespace Rcm\Repository\Redirect;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindRedirectFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindRedirect
     */
    public function __invoke($serviceContainer)
    {
        return new FindRedirect(
            $serviceContainer->get(EntityManager::class)
        );
    }
}
