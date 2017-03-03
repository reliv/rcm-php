<?php

namespace Rcm\Block\Renderer;

/**
 * @GammaRelease
 * Interface RendererRepository
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
interface RendererRepository
{
    /**
     * findByName
     *
     * @param $blockName
     *
     * @return Renderer
     */
    public function findByName($blockName);
}
