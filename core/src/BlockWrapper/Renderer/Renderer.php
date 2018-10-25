<?php

namespace Rcm\BlockWrapper\Renderer;

use Rcm\Block\InstanceWithData\InstanceWithData;
use Rcm\BlockWrapper\Instance\Instance;

/**
 * @GammaRelease
 *
 * Interface Renderer
 * @package     Rcm\Entity
 */
interface Renderer
{
    /**
     * __invoke
     *
     * @param Instance $instance
     *
     * @return string Rendered Html
     */
    public function __invoke(Instance $instance);
}
