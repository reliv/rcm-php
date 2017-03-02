<?php

namespace Rcm\Block;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @GammaRelease
 *
 * Interface BlockInstance
 * @package     Rcm\Entity
 */
interface DataProvider
{
    /**
     * Plugins automatically get their instance configs when rendering. A data service like this
     * is used to return any custom data in addition to the instance config to the plugin
     * template for rendering.
     *
     * @param Instance               $instance
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function __invoke(Instance $instance, ServerRequestInterface $request) : array;
}
