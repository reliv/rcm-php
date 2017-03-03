<?php

namespace Rcm\Block\Instance;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * Class InstanceRepositoryBcFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class InstanceRepositoryBcFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return InstanceRepositoryBc
     */
    public function __invoke($container)
    {
        return new InstanceRepositoryBc(
            $container->get(EntityManager::class)
        );
    }
}
