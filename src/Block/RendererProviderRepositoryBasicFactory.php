<?php

namespace Rcm\Block;

use Interop\Container\ContainerInterface;

/**
 * Class RendererProviderRepositoryBasicFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class RendererProviderRepositoryBasicFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return RendererProviderRepositoryBasic
     */
    public function __invoke($container)
    {
        $config = $container->get('config');
        $defaultRenderServiceName = $config['Rcm']['block-default-render'];
        return new RendererProviderRepositoryBasic(
            $container->get(ConfigRepository::class),
            $defaultRenderServiceName,
            $container
        );
    }
}
