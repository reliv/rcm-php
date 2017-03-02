<?php

namespace Rcm\Block;

use Interop\Container\ContainerInterface;

/**
 * Class DataProviderRepositoryFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DataProviderRepositoryFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return DataProviderRepository
     */
    public function __invoke($container)
    {
        return new DataProviderRepository(
            $container->get(ConfigRepository::class),
            $container
        );
    }
}
