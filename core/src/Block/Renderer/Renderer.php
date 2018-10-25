<?php

namespace Rcm\Block\Renderer;

use Rcm\Block\InstanceWithData\InstanceWithData;

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
     * @param InstanceWithData $instance
     *
     * @return string Rendered Html
     */
    public function __invoke(InstanceWithData $instance);
}
