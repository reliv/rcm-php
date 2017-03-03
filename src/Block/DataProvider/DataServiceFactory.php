<?php

namespace Rcm\Block\DataProvider;

use Interop\Container\ContainerInterface;

/**
 * Class DataServiceFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DataServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return DataService
     */
    public function __invoke($container)
    {
        return new DataService(
            $container->get(DataProviderRepository::class)
        );
    }
}
