<?php

namespace Rcm\Block\Renderer;

use Interop\Container\ContainerInterface;
use Rcm\Block\Config\ConfigRepository;

/**
 * Class RendererRepositoryFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class RendererRepositoryBasicFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return RendererRepositoryBasic
     */
    public function __invoke($container)
    {
        $config = $container->get('config');
        $rendererAliases = $config['Rcm']['block-render'];
        return new RendererRepositoryBasic(
            $container->get(ConfigRepository::class),
            $rendererAliases,
            $container
        );
    }
}
