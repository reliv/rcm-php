<?php

namespace Rcm\Block;

use Interop\Container\ContainerInterface;

/**
 * Class DataProviderRepositoryBasicFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DataProviderRepositoryBasicFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return DataProviderRepositoryBasic
     */
    public function __invoke($container)
    {
        return new DataProviderRepositoryBasic(
            $container->get(ConfigRepository::class),
            $container
        );
    }
}
