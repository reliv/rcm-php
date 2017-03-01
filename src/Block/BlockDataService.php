<?php

namespace Rcm\Block;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @depreicated - THIS IS A DRAFT. DO NOT USE IT YET.
 *
 * Interface BlockInstance
 * @package Rcm\Entity
 */
interface BlockDataService
{
    /**
     * Plugins automatically get their instance configs when rendering. A data service like this
     * is used to return any custom data in addition to the instance config to the plugin
     * template for rendering.
     *
     * @param BlockInstance $instance
     * @param ServerRequestInterface $request
     * @return array - the block custom data
     */
    public function __invoke(BlockInstance $instance, ServerRequestInterface $request);
}
