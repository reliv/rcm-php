<?php

namespace Rcm\BlockWrapper\Renderer;

use Rcm\BlockWrapper\Instance\Instance;

/**
 * @GammaRelease
 *
 * Interface Renderer
 * @package     Rcm\Entity
 */
class RendererBc implements Renderer
{
    /**
     * __invoke
     *
     * @param Instance $instance
     *
     * @return string Rendered Html
     */
    public function __invoke(Instance $instance)
    {
        // @todo
        return '';
    }
}
