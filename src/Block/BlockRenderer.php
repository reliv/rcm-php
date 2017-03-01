<?php

namespace Rcm\Block;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @depreicated - THIS IS A DRAFT. DO NOT USE IT YET.
 *
 * Interface BlockInstance
 * @package Rcm\Entity
 */
interface BlockRenderer
{
    /**
     * __invoke
     *
     * @param BlockInstance          $instance
     * @param ServerRequestInterface $request
     *
     * @return string Rendered Html
     */
    public function __invoke(BlockInstance $instance, ServerRequestInterface $request);
}
