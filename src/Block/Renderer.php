<?php

namespace Rcm\Block;

/**
 * @depreicated - THIS IS A DRAFT. DO NOT USE IT YET.
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
