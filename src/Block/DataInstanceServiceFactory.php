<?php

namespace Rcm\Block;

use Interop\Container\ContainerInterface;

/**
 * Class DataInstanceServiceFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DataInstanceServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return DataInstanceService
     */
    public function __invoke($container)
    {
        return new DataInstanceService(
            $container->get(InstanceRepository::class),
            $container->get(DataService::class)
        );
    }
}
