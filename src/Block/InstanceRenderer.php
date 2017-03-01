<?php

namespace Rcm\Block;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @depreicated - THIS IS A DRAFT. DO NOT USE IT YET.
 *
 * Interface BlockInstance
 * @package     Rcm\Entity
 */
interface InstanceRenderer
{
    /**
     * __invoke
     *
     * @param Instance               $instance
     * @param ServerRequestInterface $request
     *
     * @return string Rendered Html
     */
    public function __invoke(Instance $instance, ServerRequestInterface $request);
}
