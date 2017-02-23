<?php

namespace Rcm\Renderer;

use Psr\Http\Message\ServerRequestInterface;
use Rcm\Entity\BlockInstance;

/**
 * @todo FUTURE
 * Class Block
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
interface Block
{
    /**
     * __invoke
     *
     * @param ServerRequestInterface $request
     * @param BlockInstance          $instance
     *
     * @return string Rendered Html
     */
    public function __invoke(ServerRequestInterface $request, BlockInstance $instance);
}
