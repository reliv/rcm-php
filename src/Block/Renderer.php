<?php

namespace Rcm\Block;

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
     * @param Data $instance
     *
     * @return string Rendered Html
     */
    public function __invoke(Data $instance);
}
