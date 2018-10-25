<?php

namespace Rcm\Block\Instance;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Rcm\Block\Config\ConfigRepository;

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
            $container->get(EntityManager::class),
            $container->get(ConfigRepository::class),
            $container->get(InstanceConfigMerger::class)
        );
    }
}
