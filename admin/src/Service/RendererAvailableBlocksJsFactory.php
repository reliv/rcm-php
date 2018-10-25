<?php

namespace RcmAdmin\Service;

use Interop\Container\ContainerInterface;
use Rcm\Block\Config\ConfigRepository;

/**
 * Class RendererAvailableBlocksJsFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class RendererAvailableBlocksJsFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return RendererAvailableBlocksJs
     */
    public function __invoke($container)
    {
        return new RendererAvailableBlocksJs(
            $container->get(ConfigRepository::class)
        );
    }
}
