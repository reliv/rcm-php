<?php

namespace Rcm\Block\InstanceWithData;

use Interop\Container\ContainerInterface;
use Rcm\Block\DataProvider\DataService;
use Rcm\Block\Instance\InstanceRepository;

/**
 * Class InstanceWithDataServiceFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class InstanceWithDataServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return InstanceWithDataService
     */
    public function __invoke($container)
    {
        return new InstanceWithDataService(
            $container->get(InstanceRepository::class),
            $container->get(DataService::class)
        );
    }
}
