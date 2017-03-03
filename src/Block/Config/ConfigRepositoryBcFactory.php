<?php

namespace Rcm\Block\Config;

use Interop\Container\ContainerInterface;
use Rcm\Service\Cache;

/**
 * Class ConfigRepositoryBcFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ConfigRepositoryBcFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return ConfigRepositoryBc
     */
    public function __invoke($container)
    {
        $config = $container->get('config');

        return new ConfigRepositoryBc(
            $config['cmPlugins'],
            $container->get(Cache::class),
            $container->get(ConfigFields::class),
            $container->get(ConfigRepositoryJson::class)
        );
    }
}
