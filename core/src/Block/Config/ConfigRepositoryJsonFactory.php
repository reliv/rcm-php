<?php

namespace Rcm\Block\Config;

use Interop\Container\ContainerInterface;
use Rcm\Service\Cache;

/**
 * Class ConfigRepositoryJsonFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ConfigRepositoryJsonFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return ConfigRepositoryJson
     */
    public function __invoke($container)
    {
        $config = $container->get('config');
        return new ConfigRepositoryJson(
            $config['Rcm']['blocks'],
            $container->get(Cache::class),
            $container->get(ConfigFields::class)
        );
    }
}
